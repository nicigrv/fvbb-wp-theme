# Förderverein Biedensand Bäder – WordPress-Theme

Eigenständiges Theme für [fv-biedensand-baeder.de](https://fv-biedensand-baeder.de/) (kein Parent-Theme, kein Page-Builder).
Ersetzt EmpowerWP/Mesmerize. Gehört zum Plugin [fvbb-wp-core](https://github.com/nicigrv/fvbb-wp-core) („Förderverein Funktionen“).

Logo, Kopfbild, Titel, Instagram-Button, Menü und Footer-Spalten werden aus den alten Theme-Einstellungen übernommen.

## Updates auf der Website
Das Theme fragt das neueste [Release](../../releases) ab. Neue Versionen erscheinen in WordPress unter
**Dashboard → Aktualisierungen** und werden dort wie gewohnt installiert („Erneut überprüfen“ prüft sofort,
sonst prüft WordPress automatisch alle paar Stunden).

## Neue Version veröffentlichen
1. Änderungen machen und die Versionsnummer erhöhen in `style.css` (`Version:`) **und** `functions.php` (`FVBB_THEME_VERSION`), z. B. auf `1.0.1`.
2. Commit auf `main` pushen. Die Commit-Nachrichten seit der letzten Version erscheinen in WordPress als Änderungsliste.
3. Die GitHub-Action merkt die neue Versionsnummer, baut die ZIP und legt das Release `v1.0.1` an.
   Ohne neue Versionsnummer wird kein Release erstellt.

## Aufbau
| Pfad | Inhalt |
|---|---|
| `style.css`, `theme.json` | Theme-Kopf, Farben (Sonnengelb, Vereinsblau, Wellenblau, Sand, Wasser), Schrift, Abstände |
| `assets/css/theme.css`, `assets/js/theme.js` | Design, Navigation, Kopfbild |
| `inc/customizer.php` | Design → Anpassen → „Förderverein“, Übernahme der alten Einstellungen |
| `inc/class-github-updater.php` | Updates aus GitHub-Releases |
| `patterns/` | Muster im Editor (Vorstand, Bankverbindung, Info-Box, Text + Bild) |

Lizenz: GPL-3.0-or-later. Schrift: Mulish (SIL Open Font License, siehe `assets/fonts/`).
