#!/bin/ksh
<<<<<<< HEAD
# Nettoyage des export GPARC

echo "********************************"
echo "*** M�nage des exports GPARC ***"
=======

# Nettoyage des export GPARC
echo "********************************"
echo "*** Ménage des exports GPARC ***"
>>>>>>> 2.7.0
echo "********************************"
racine_csv="/home/data/evpos/$ENV/gparc"
for file in `ls $racine_csv/*.csv`
do
  cat $file | sed -e 's/\"//g' | sed -e "s/'/''/g" > $file.clean
  rm $file
  mv $file.clean $file
done

<<<<<<< HEAD

=======
>>>>>>> 2.7.0
# Mise à jour des données de suivi à partir des réfgérentiels
for cmd in import_baza import_suapp update_avancement import_poste import_acces_appli import_acces_uo import_acces_poste report_acces_poste report_acces_service update_avancement import_riu update_historique import_imprimantes update_comptage update_silo_appli
do
    echo "*********************************************"
    echo "*** Lancement de la commande $cmd "
    echo "*********************************************"
    /home/apache/apache-2.2.24/php-5.5.4/bin/php -c $HOME/Apache/conf app/console evpos:$cmd
done
