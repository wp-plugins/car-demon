// JavaScript Document
function returnPayment() {
	var Principal = document.calc.pv.value
	Principal = Principal.replace(',','');
	if (document.calc.rate.value==0) {
		var Rate = 0.000000001
	} else {
		var Rate = (document.calc.rate.value/100)/12
	}
	var Rate = (document.calc.rate.value/100)/12
	var Term = document.calc.numPmtYr.value
	document.calc.pmt.value ="$" + find_payment(Principal, Rate, Term);
}
function find_payment(PR, IN, PE) {
	var PAY = ((PR * IN) / (1 - Math.pow(1 + IN, -PE)));
	return PAY.toFixed(2);
}