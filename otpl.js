function generatePassword(fieldId = "password", format = "Abcde##$") {
  password = '';
  for(var i=0; i<format.length; i++) {
    if(format[i] == '#') password += randomNumber();
    else if(format[i] == '$') password += randomSpecialCharacter();
    else if(isUpperCase(format[i])) password += randomUpperCase();
    else if(isLowerCase(format[i])) password += randomLowerCase();
  }
  document.getElementById(fieldId).value = password;
}

function isUpperCase(character) {
  if(character == character.toUpperCase()) return true;
  else return false;
}

function isLowerCase(character) {
  if(character == character.toLowerCase()) return true;
  else return false;
}

function randomLowerCase() {
  var chars = "abcdefghiklmnopqrstuvwxyz";
  var rnum = Math.floor(Math.random() * chars.length);
  return chars.substring(rnum,rnum+1);
}

function randomUpperCase() {
  var chars = "ABCDEFGHIKLMNOPQRSTUVWXYZ";
  var rnum = Math.floor(Math.random() * chars.length);
  return chars.substring(rnum,rnum+1);
}

function randomSpecialCharacter() {
  var chars = "!@#$?";
  var rnum = Math.floor(Math.random() * chars.length);
  return chars.substring(rnum,rnum+1);
}

function randomNumber() {
  return Math.floor(Math.random() * 9);
}
