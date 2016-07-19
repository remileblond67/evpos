#!/bin/ksh
# Mise à jour des données de suivi à partir des réfgérentiels
for cmd in import_baza import_suapp update_avancement import_poste import_acces_appli import_acces_uo import_acces_poste report_acces_poste report_acces_service update_avancement import_riu update_historique import_imprimantes update_comptage update_silo_appli
do
    echo "*********************************************"
    echo "*** Lancement de la commande $cmd "
    echo "*********************************************"
    /home/apache/apache-2.2.24/php-5.5.4/bin/php -c $HOME/Apache/conf app/console evpos:$cmd
done
