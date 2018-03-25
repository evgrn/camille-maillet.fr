$(function(){

  /**
   * Bloque le formulaire si les champs entrés en paramètres ne correspondent pas entre eux.
   * @type {Object}
   */
  var pwdConfirm = {
    pwdSelector: '',
    pwdConfirmSelector: '',
    formSelector: '',
    submitSelector: '',

    /**
     * Initialise l'objet : empêche la soumission du formulaire, insère les conteneurs des messages d'erreur
     * et initialise l'écoute du clic sur le bouton de soumission du formulaire.
     * @param  {string} pwdSelector        Sélecteur du champ mot de passe
     * @param  {string} pwdConfirmSelector Sélecteur du champ de confirmation du mot de passe
     * @param  {string} formSelector       Sélecteur du formulaire
     * @param  {string} submitSelector     Sélecteur du bouton de soumission du formulaire
     */
    init: function(pwdSelector, pwdConfirmSelector, formSelector, submitSelector){
      this.pwdSelector = formSelector + ' ' + pwdSelector;
      this.pwdConfirmSelector = formSelector + ' ' + pwdConfirmSelector;
      this.formSelector = formSelector;
      this.submitSelector = formSelector + ' ' +  submitSelector;

      $(this.formSelector).bind("submit", this.preventdefault);
      this.insertErrorMessageContainers();
      this.initEvents();
    },

    /**
     * Empêche l'action liée à l'élément de s'executer.
     * @param  {event} e [description] //?
     */
    preventdefault: function(e){
        e.preventDefault();
    },

    /**
     * Initialise l'écoute des évènements : si les mots de passe correspondent, autorise la soumission du formulaire,
     * sinon affiche un message d'erreur.
     */
    initEvents: function(){
      $(this.submitSelector).on('click', function(e){

        if(pwdConfirm.checkCorrespondance()){
          pwdConfirm.unbindSubmit();
        }
        else{
         pwdConfirm.mismatchMessage();
        }
      });
    },

    /**
     * Vérifie si les mots de passe coffespondent.
     * @return {bool} Si oui retourne true, sinon false
     */
    checkCorrespondance: function(){
      if($(this.pwdSelector).val() === $(this.pwdConfirmSelector).val()){
        return true;
      }
      return false;
    },

    /**
     * Annule l'empêchement de soumission du formulaire.
     */
    unbindSubmit: function(){
        $(this.formSelector).unbind("submit", this.preventdefault);
    },

    /**
     * Affiche un message d'erreur sous les champs des mots de passe.
     */
    mismatchMessage: function(){
      $('.pwd-missmatch-message').text('Les mots de passes ne correspondent pas.')
    },

    /**
     * Affiche les conteneurs des messages d'erreur sous les champs à comparer.
     */
    insertErrorMessageContainers: function(){
      $(this.pwdSelector).after('<div class="pwd-missmatch-message"></div>');
      $(this.pwdConfirmSelector).after('<div class="pwd-missmatch-message"></div>');
    }

  };

  pwdConfirm.init('#user_password_first', '#user_password_second', 'form#user-edit', '.btn');

});
