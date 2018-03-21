$(function(){


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


$('.form-group.date input').datepicker({
    dateFormat: 'dd/mm/yy',
    firstDay: 1
});


});
