$(function(){

    // Initialisation de TinyMCE
      tinymce.init({
       selector: "textarea",
       language_url: '/js/tinymce/langs/fr_FR.js',
       language: 'fr_FR',
       menubar: false,
       plugins: [
         'advlist autolink lists link image charmap print preview anchor textcolor',
         'searchreplace visualblocks code fullscreen',
         'insertdatetime media table contextmenu paste code help wordcount'
       ],
       toolbar: 'insert | undo redo | bold italic | bullist numlist outdent indent | removeformat',
      });


    //### Initialisation de jQuery UI DatePicker ###//

    // Traduction en français
    $.datepicker.regional['fr'] = {clearText: 'Effacer', clearStatus: '',
        closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
        prevText: '&lt;Préc', prevStatus: 'Voir le mois précédent',
        nextText: 'Suiv&gt;', nextStatus: 'Voir le mois suivant',
        currentText: 'Courant', currentStatus: 'Voir le mois courant',
        monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
            'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
        monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
            'Jul','Aoû','Sep','Oct','Nov','Déc'],
        monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
        weekHeader: 'Sm', weekStatus: '',
        dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
        dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
        dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
        dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
        dateFormat: 'dd/mm/yy', firstDay: 0,
        initStatus: 'Choisir la date', isRTL: false};
    $.datepicker.setDefaults($.datepicker.regional['fr']);

    // Assignation aux champs date
    $('.form-group.date input').datepicker({
        dateFormat: 'dd/mm/yy',
        firstDay: 1
    });


});
