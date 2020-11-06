class Confirmation {
    deliveryTermsModal;
    termCheckbox;
    confirmButton;
    constructor() {
        this.deliveryTermsModal = document.getElementById('delivery-terms-modal');
        this.termCheckbox = document.getElementById('term-checkbox');
        this.confirmButton = document.getElementById('confirm-button');
    }

    termsChecked() {
        this.confirmButton.disabled = !this.termCheckbox.checked;
    }
}

