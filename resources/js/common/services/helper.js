export function capitalize(string) {
    if (!string) {
        return;
    }

    return string
        .split('_')
        .map((word) => {
            return word[0].toUpperCase() + word.substr(1).toLowerCase();
        })
        .join(' ');
}

export function getDisplayableColumns(columns) {
    return Object.values(columns).filter((column) => {
        return column.isDisplay;
    });
}

export function areColumnsCustomized(originalColumns, localStorageColumns) {
    if (originalColumns.length !== localStorageColumns.length) {
        return true;
    }

    for (const key in originalColumns) {
        let isColumnFound = false;

        for (const localStorageColumnKey in localStorageColumns) {
            const originalColumn = Object.assign({}, originalColumns[key]);
            delete originalColumn.isDisplay;

            const localStorageColumn = Object.assign(
                {},
                localStorageColumns[localStorageColumnKey],
            );
            delete localStorageColumn.isDisplay;

            if (
                JSON.stringify(originalColumn) ===
                JSON.stringify(localStorageColumn)
            ) {
                isColumnFound = true;
            }
        }

        if (!isColumnFound) {
            return true;
        }
    }

    return false;
}

export function getDisplayDate(date) {
    const dateObject = new Date(date);
    const options = {
        timeZone: 'Asia/Kuala_Lumpur', // TODO: In-Future make it dynamic based on user settings.
        day: 'numeric',
        month: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: true,
    };

    return new Intl.DateTimeFormat('en-MY', options).format(dateObject);
}

export function getTodayDateInYMD() {
    const dateObject = new Date();
    const options = {
        timeZone: 'Asia/Kuala_Lumpur', // TODO: In-Future make it dynamic based on user settings.
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    };

    const formatter = new Intl.DateTimeFormat('en-MY', options);
    const [{ value: day }, , { value: month }, , { value: year }] =
        formatter.formatToParts(dateObject);

    return `${year}-${month}-${day}`;
}

export const getPreparedFormData = (form) => {
    const formData = makeFormData(form);

    if (form.logo) {
        formData.append('logo', form.logo);
    }

    if (form.photo) {
        formData.append('photo', form.photo);
    }

    return formData;
};

export function makeFormData(form) {
    const formData = new FormData();

    for (var key in form) {
        // https://eslint.org/docs/rules/no-prototype-builtins
        if (Object.prototype.hasOwnProperty.call(form, key)) {
            // if (form[key] && form[key].constructor === Array) {
            //     for (const arrayElement of form[key]) {
            //         formData.append(key + '[]', arrayElement);
            //     }
            // } else {
            if (form[key] === null) {
                // https://developer.mozilla.org/en-US/docs/Web/API/FormData/append#Example
                // FormData.append converted null to "null"
                formData.append(key, (form[key] = ''));
            } else if (form[key] === true) {
                formData.append(key, 1);
            } else if (form[key] === false) {
                formData.append(key, 0);
            } else {
                formData.append(key, form[key]);
            }
            // }
        }
    }

    return formData;
}
