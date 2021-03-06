/* global Swal axios ajaxUrl */

function normalize(str) {
    return str
        .toString()
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
}

function getLetterType() {
    const datatypes = document.getElementById('datatype')

    if (datatypes) {
        return JSON.parse(document.getElementById('datatype').innerHTML)
    }
}

function removeItemAjax(id, podType, podName, callback) {
    Swal.fire({
        title: 'Opravdu chcete smazat tuto položku?',
        type: 'warning',
        buttonsStyling: false,
        showCancelButton: true,
        confirmButtonText: 'Ano!',
        cancelButtonText: 'Zrušit',
        confirmButtonClass: 'btn btn-primary btn-lg mr-1',
        cancelButtonClass: 'btn btn-secondary btn-lg ml-1',
    }).then((result) => {
        if (result.value) {
            axios
                .post(
                    ajaxUrl + '?action=delete_hiko_pod',
                    {
                        ['pod_type']: podType,
                        ['pod_name']: podName,
                        ['id']: id,
                    },
                    {
                        headers: {
                            'Content-Type': 'application/json;charset=utf-8',
                        },
                    }
                )
                .then(function () {
                    Swal.fire({
                        title: 'Odstraněno.',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonText: 'OK',
                        confirmButtonClass: 'btn btn-primary btn-lg',
                    })
                    callback()
                })
                .catch(function (error) {
                    Swal.fire({
                        title: 'Při odstraňování došlo k chybě.',
                        text: error,
                        type: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'OK',
                        confirmButtonClass: 'btn btn-primary btn-lg',
                    })
                })
        }
    })
}

function decodeHTML(str) {
    let txt = document.createElement('textarea')
    txt.innerHTML = str
    return txt.value
}

function updateTableHeaders() {
    document.querySelectorAll('.tabulator-header-filter').forEach((item) => {
        item.querySelector('input').classList.add(
            'form-control',
            'form-control-sm'
        )
    })
}

function arrayToList(arr) {
    if (!Array.isArray(arr)) {
        return arr
    }

    let list = ''

    arr.forEach((item) => {
        list += `<li>${item}</li>`
    })

    return `<ul class="list-unstyled mb-0">${list}</ul>`
}
