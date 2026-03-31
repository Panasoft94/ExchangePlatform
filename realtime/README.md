# Salon local de reunion

Ce projet utilise un salon audio WebRTC local pour le module reunion.

## Prerequis

- Node.js installe sur la machine ou le serveur local
- Navigateur avec acces au micro
- Les participants doivent pouvoir joindre le port `8081` du serveur local

## Lancement

Depuis la racine du projet:

```bash
npm install
npm run visio:signal
npm run visio:turn
```

Sous Windows, vous pouvez aussi utiliser directement:

```bat
start-local-reunion.bat
```

Ce script tente de lancer WampServer puis ouvre le serveur de signalisation local dans une nouvelle fenetre.
Il ouvre aussi un serveur TURN local pour ameliorer la connectivite WebRTC hors du reseau strictement local.

Une page d'administration est aussi disponible dans l'application via `Visioconference > Config RTC` pour modifier l'hote RTC, le port de signalisation et les identifiants TURN sans editer les fichiers a la main.

## Port

Par defaut, le serveur de signalisation ecoute sur le port `8081`.
Vous pouvez le changer avec la variable d'environnement `REUNION_SIGNAL_PORT`.

Le serveur TURN local ecoute par defaut sur le port `3478`.
Vous pouvez ajuster sa configuration dans `realtime/turn-config.json`.

Les serveurs ICE utilises par la salle sont definis dans `realtime/ice-servers.json`.
Le port WebSocket partage est stocke dans `realtime/app-config.json`.

## Limites

- Cette implementation est locale et pair-a-pair
- Le serveur TURN local integre ameliore les connexions, mais reste une base legere pour intranet et petits groupes
- Pour un usage internet large ou mobile, il faudra renforcer l'infrastructure RTC interne et la securisation reseau
