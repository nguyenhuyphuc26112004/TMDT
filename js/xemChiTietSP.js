var amountElement = document.getElementById('amount');
var amount = parseInt(amountElement.value);
function render(amount) {
    amountElement.value = amount;
}
function tang() {
    // var amount = parseInt(amountElement.value);
    amount++;
    render(amount);
}
function giam() {
    // var amount = parseInt(amountElement.value);
    if (amount > 1) {
    amount--;
    }
    render(amount);
}