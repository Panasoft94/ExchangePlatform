const fs = require('fs');
const path = require('path');
const WebSocket = require('ws');

const configPath = path.join(__dirname, 'app-config.json');
let port = parseInt(process.env.REUNION_SIGNAL_PORT || '0', 10);

if (port <= 0 && fs.existsSync(configPath)) {
  try {
    const config = JSON.parse(fs.readFileSync(configPath, 'utf8'));
    port = parseInt(config.signalPort || '0', 10);
  } catch (error) {
    port = 0;
  }
}

if (port <= 0) {
  port = 8081;
}

const server = new WebSocket.Server({ port });
const rooms = new Map();
const roomChatHistory = new Map();
const MAX_CHAT_HISTORY = 200;

function createId() {
  return Math.random().toString(36).slice(2, 10);
}

function getRoom(roomId) {
  if (!rooms.has(roomId)) {
    rooms.set(roomId, new Map());
  }
  return rooms.get(roomId);
}

function getChatHistory(roomId) {
  if (!roomChatHistory.has(roomId)) {
    roomChatHistory.set(roomId, []);
  }
  return roomChatHistory.get(roomId);
}

function roomPeers(roomId, excludeClientId) {
  const room = rooms.get(roomId);
  if (!room) {
    return [];
  }

  const peers = [];
  room.forEach((peer, clientId) => {
    if (clientId === excludeClientId) {
      return;
    }

    peers.push({
      clientId,
      userId: peer.userId,
      displayName: peer.displayName,
      avatarUrl: peer.avatarUrl,
      isHost: !!peer.isHost,
      handRaised: !!peer.handRaised,
      micEnabled: peer.micEnabled !== false,
      cameraEnabled: peer.cameraEnabled !== false,
      screenSharing: !!peer.screenSharing
    });
  });

  return peers;
}

function broadcast(roomId, payload, excludeClientId) {
  const room = rooms.get(roomId);
  if (!room) {
    return;
  }

  const message = JSON.stringify(payload);
  room.forEach((peer, clientId) => {
    if (clientId === excludeClientId) {
      return;
    }

    if (peer.socket.readyState === WebSocket.OPEN) {
      peer.socket.send(message);
    }
  });
}

function broadcastAll(roomId, payload) {
  const room = rooms.get(roomId);
  if (!room) {
    return;
  }

  const message = JSON.stringify(payload);
  room.forEach((peer) => {
    if (peer.socket.readyState === WebSocket.OPEN) {
      peer.socket.send(message);
    }
  });
}

function broadcastParticipants(roomId) {
  broadcast(roomId, {
    type: 'participants',
    peers: roomPeers(roomId)
  });
}

function leaveRoom(socket) {
  if (!socket.roomId || !socket.clientId) {
    return;
  }

  const room = rooms.get(socket.roomId);
  if (!room) {
    return;
  }

  room.delete(socket.clientId);
  broadcast(socket.roomId, {
    type: 'peer-left',
    clientId: socket.clientId
  }, socket.clientId);
  broadcastParticipants(socket.roomId);

  if (room.size === 0) {
    rooms.delete(socket.roomId);
    roomChatHistory.delete(socket.roomId);
  }

  socket.roomId = null;
  socket.clientId = null;
}

server.on('connection', (socket) => {
  socket.on('message', (raw) => {
    let message;

    try {
      message = JSON.parse(raw.toString());
    } catch (error) {
      return;
    }

    if (message.type === 'join') {
      const roomId = String(message.roomId || '').trim();
      if (!roomId) {
        return;
      }

      leaveRoom(socket);

      const clientId = createId();
      const room = getRoom(roomId);
      socket.roomId = roomId;
      socket.clientId = clientId;

      room.set(clientId, {
        socket,
        userId: parseInt(message.userId || 0, 10),
        displayName: String(message.displayName || 'Participant'),
        avatarUrl: String(message.avatarUrl || ''),
        isHost: !!message.isHost,
        handRaised: !!message.handRaised,
        micEnabled: message.micEnabled !== false,
        cameraEnabled: message.cameraEnabled !== false,
        screenSharing: false
      });

      const chatHistory = getChatHistory(roomId);

      socket.send(JSON.stringify({
        type: 'joined',
        clientId,
        peers: roomPeers(roomId, clientId),
        chatHistory: chatHistory.slice(-50)
      }));

      broadcast(roomId, {
        type: 'system-message',
        text: String(message.displayName || 'Participant') + ' a rejoint la réunion',
        timestamp: Date.now()
      }, clientId);

      broadcastParticipants(roomId);
      return;
    }

    if (message.type === 'leave') {
      const room = rooms.get(socket.roomId || '');
      const peer = room ? room.get(socket.clientId) : null;
      if (peer) {
        broadcast(socket.roomId, {
          type: 'system-message',
          text: peer.displayName + ' a quitté la réunion',
          timestamp: Date.now()
        }, socket.clientId);
      }
      leaveRoom(socket);
      return;
    }

    const room = rooms.get(socket.roomId || '');
    if (!room) {
      return;
    }

    const currentPeer = room.get(socket.clientId);
    if (!currentPeer) {
      return;
    }

    if (message.type === 'participant-state') {
      currentPeer.handRaised = !!message.handRaised;
      currentPeer.micEnabled = message.micEnabled !== false;
      currentPeer.cameraEnabled = message.cameraEnabled !== false;
      currentPeer.screenSharing = !!message.screenSharing;
      broadcastParticipants(socket.roomId);
      return;
    }

    if (message.type === 'chat-message') {
      const text = String(message.text || '').trim();
      if (!text || text.length > 2000) {
        return;
      }

      const chatMsg = {
        id: createId(),
        clientId: socket.clientId,
        userId: currentPeer.userId,
        displayName: currentPeer.displayName,
        avatarUrl: currentPeer.avatarUrl,
        text: text,
        timestamp: Date.now()
      };

      const history = getChatHistory(socket.roomId);
      history.push(chatMsg);
      if (history.length > MAX_CHAT_HISTORY) {
        history.splice(0, history.length - MAX_CHAT_HISTORY);
      }

      broadcastAll(socket.roomId, {
        type: 'chat-message',
        message: chatMsg
      });
      return;
    }

    if (message.type === 'host-mute-all') {
      if (!currentPeer.isHost) {
        return;
      }

      broadcast(socket.roomId, {
        type: 'force-mute'
      }, socket.clientId);

      broadcastAll(socket.roomId, {
        type: 'system-message',
        text: currentPeer.displayName + ' a coupé le micro de tous les participants',
        timestamp: Date.now()
      });
      return;
    }

    if (message.type === 'host-end-meeting') {
      if (!currentPeer.isHost) {
        return;
      }

      broadcastAll(socket.roomId, {
        type: 'meeting-ended',
        text: 'La réunion a été terminée par ' + currentPeer.displayName
      });
      return;
    }

    if (message.type === 'screen-share-started') {
      currentPeer.screenSharing = true;
      broadcastParticipants(socket.roomId);
      broadcast(socket.roomId, {
        type: 'system-message',
        text: currentPeer.displayName + ' partage son écran',
        timestamp: Date.now()
      }, socket.clientId);
      return;
    }

    if (message.type === 'screen-share-stopped') {
      currentPeer.screenSharing = false;
      broadcastParticipants(socket.roomId);
      return;
    }

    const target = room.get(message.target);
    if (!target || target.socket.readyState !== WebSocket.OPEN) {
      return;
    }

    if (message.type === 'offer' || message.type === 'answer' || message.type === 'ice-candidate') {
      target.socket.send(JSON.stringify({
        type: message.type,
        from: socket.clientId,
        peer: currentPeer
          ? {
              clientId: socket.clientId,
              userId: currentPeer.userId,
              displayName: currentPeer.displayName,
              avatarUrl: currentPeer.avatarUrl,
              isHost: !!currentPeer.isHost,
              handRaised: !!currentPeer.handRaised,
              micEnabled: currentPeer.micEnabled !== false,
              cameraEnabled: currentPeer.cameraEnabled !== false,
              screenSharing: !!currentPeer.screenSharing
            }
          : null,
        sdp: message.sdp || null,
        candidate: message.candidate || null
      }));
    }
  });

  socket.on('close', () => {
    const room = rooms.get(socket.roomId || '');
    const peer = room ? room.get(socket.clientId) : null;
    if (peer && socket.roomId) {
      broadcast(socket.roomId, {
        type: 'system-message',
        text: peer.displayName + ' s\'est déconnecté',
        timestamp: Date.now()
      }, socket.clientId);
    }
    leaveRoom(socket);
  });

  socket.on('error', () => {
    leaveRoom(socket);
  });
});

console.log('Reunion signal server listening on port ' + port);
