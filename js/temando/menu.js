function temandoMenu() {

    var allSpans = document.getElementsByTagName("span");
    Array.prototype.forEach.call(allSpans, function(span) {

        if (span.textContent === 'Partial Shipments - Business plan' || span.textContent === 'Multi Warehouse - Business plan' || span.textContent === 'Advance Shipping Rules - Business plan') {
            span.setStyle({backgroundColor: '#E5E5E5'});
            span.setStyle({color: '#999999'});
            span.up().up().setStyle({
                color: '#DFDFDF'
            });
        }
    });
}