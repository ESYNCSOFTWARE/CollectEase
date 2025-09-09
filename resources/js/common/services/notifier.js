import alertify from 'alertifyjs';

alertify.set('notifier', 'position', 'top-right');

export const confirmDialogBox = (
    message,
    onSuccess,
    onCancel = function () {},
) => {
    const dialog = alertify
        .confirm('Confirmation', message, onSuccess, onCancel)
        .set('labels', { ok: 'Yes', cancel: 'No' });

    // Style OK button (orange)
    const footer = dialog.elements.footer;
    const okButton = footer.querySelector('.ajs-ok');
    if (okButton) {
        okButton.style.backgroundColor = 'orange';
        okButton.style.borderColor = 'orange';
        okButton.style.color = 'white';
        okButton.style.cursor = 'pointer';
        okButton.style.borderRadius = '0.375rem'; // rounded corners
        okButton.addEventListener('mouseover', () => {
            okButton.style.backgroundColor = '#ff9900';
        });
        okButton.addEventListener('mouseout', () => {
            okButton.style.backgroundColor = 'orange';
        });
    }

    // Style Cancel button
    const cancelButton = footer.querySelector('.ajs-cancel');
    if (cancelButton) {
        cancelButton.style.backgroundColor = 'red';
        cancelButton.style.color = 'white';
        cancelButton.style.cursor = 'pointer';
        cancelButton.style.borderRadius = '0.375rem';
    }
};

export const confirmDialogBoxWithCenterText = (
    message,
    onSuccess,
    onCancel = function () {},
) => {
    const dialog = alertify
        .confirm('Confirmation', message, onSuccess, onCancel)
        .set('labels', { ok: 'Yes', cancel: 'No' });

    const footer = dialog.elements.footer;
    const okButton = footer.querySelector('.ajs-ok');
    if (okButton) {
        okButton.style.backgroundColor = 'orange';
        okButton.style.borderColor = 'orange';
        okButton.style.color = 'white';
        okButton.style.cursor = 'pointer';
        okButton.style.borderRadius = '0.375rem';
        okButton.classList.add('text-center-important');
        okButton.addEventListener('mouseover', () => {
            okButton.style.backgroundColor = '#ff9900';
        });
        okButton.addEventListener('mouseout', () => {
            okButton.style.backgroundColor = 'orange';
        });
    }

    const cancelButton = footer.querySelector('.ajs-cancel');
    if (cancelButton) {
        cancelButton.style.backgroundColor = '#ccc';
        cancelButton.style.color = '#000';
        cancelButton.style.cursor = 'pointer';
        cancelButton.style.borderRadius = '0.375rem';
    }

    // Center text
    dialog.elements.header.classList.add('text-center-important');
    dialog.elements.content.classList.add('text-center-important');
};

export const showSuccessNotification = (message) => {
    let timer;
    const notification = alertify.success(message);
    delayMessage(timer, notification);
};

export const showErrorNotification = (message) => {
    let timer;
    const notification = alertify.error(message);
    delayMessage(timer, notification);
};

const delayMessage = (timer, notification) => {
    const delay = alertify.get('notifier', 'delay');
    if (delay) {
        notification.element.addEventListener('mouseover', function () {
            notification.delay(delay);

            timer = setInterval(function () {
                notification.delay(delay);
            }, 600 * delay);
        });

        notification.element.addEventListener('mouseout', function () {
            clearInterval(timer);
        });
    }
};
