EVPOS : base de suivi de la migration MOCA
=====

A Symfony project created on June 24, 2015, 9:40 am.

Finalités de l'application
----
Cette application a pour but de faciliter la planification et le suivi de la migration MOCA au sein de l'Eurométropole de Strasbourg.

Elle doit permettre :
- de **prioriser** la migration des applications, en identifiant celles qui sont le plus utilisées.
- d'aider à définir le planing de déploiement des différents services, en **consitituant des groupes cohérents** (utilisation de groupes d'applications communes).
- de valider les **besoins applicatifs des services** avant migration
- de prévoir les **types de postes à migrer** en fonction de l'usage réel constaté (import d'extractions Nexthink).

Annuaire
---

On regroupe ici les informations relatives aux directions, services et utilisateurs. 

Ces informations relatives aux services, directions et utilisateurs sont récupérées de **BAZA**.

Patrimoine applicatif
---

Reprise des informations de **SUAPP** relatives aux applications et à leurs UO. Seules les applications contenant au moins une UO à migrer dans MOCA sont reprises. L'ensemble des UO est repris, même celles qui ne doivent pas être migrées dans MOCA (pour permettre leur identification dans Nexthink).

Usage
---

Les usages suivants sont récupérés des sources suivantes :

- **GAP** : affectation des utilisateurs aux applications
- **Fichiers licences** (sur T:\Licences) : affectation des applications aux postes de travail *reste à faire*
- **Nexthink** :
 - Utilisation des postes de travail *reste à faire*
 - Utilisation effective des applications *reste à faire*
 
L'application permet également d'extraire les règles permettant d'identifier, dans Nexthink, les applications et leurs UO (export XML).
