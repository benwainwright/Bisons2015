/*** Source: https://github.com/johnantoni/jquery.validate.filters ***/
jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
  return this.optional(element) || phone_number.length > 9 &&
  phone_number.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/);
  }, 'Please specify a valid UK phone number'
);

jQuery.validator.addMethod("postalCode", function(value, element) { 
// Regex here is my own
  return this.optional(element) || /^[A-Za-z]{1,2}[0-9]{1,2}\s?[0-9]{1}[a-zA-Z]{2}$/i.test(value);
}, 'Please enter a valid UK postal code');


jQuery.validator.addMethod('mobileUK', function(phone_number, element) {
  return this.optional(element) || phone_number.length > 9 &&
  phone_number.match(/^((0|\+44)7(5|6|7|8|9){1}\d{2}\s?\d{6})$/);
  }, 'Please specify a valid mobile number'
);
/*********************************************************************/
