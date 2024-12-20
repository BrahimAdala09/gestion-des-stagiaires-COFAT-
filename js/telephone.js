function validatePhoneNumber() {
    var telephone = document.getElementById('telephone').value;
    
       if (telephone.length !== 8 || isNaN(telephone)) {
        alert('Le numéro de téléphone doit contenir exactement 8 chiffres.');
        return false;
    }
    
    return true;
}