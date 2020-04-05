//=======================================================================
// ====================  private Functions ==============================
//=======================================================================

function __(selector) {
    const self = {
        element: document.querySelector(selector),
        html: () => self.element,
        on: (event, callback) => {
            document.addEventListener(event, callback)
        },
        hide: () => {
            self.element.style.display = "none"
        },
        show: () => {
            self.element.style.display = "block"
        },
        attr: (name, value) => {
            if (value == null)
                self.element.getAttribute(name)
            else
                self.element.setAttribute(name, value)
        },
        error: (class_name) => {
            if (class_name == null)
                self.element.style.color = "red"
            else
                self.element.addClass(class_name)
        }
        ,
        success: (class_name) => {
            if (class_name == null)
                self.element.style.color = "green"
            else
                self.element.addClass(class_name)
        },
        turkishToEnglish: (value) => {
            if (self.element != null && value==null) {
                // value = self.element.textContent;
                self.element.textContent = self.element.textContent.replace('Ğ', 'g')
                    .replace('Ü', 'u')
                    .replace('Ş', 's')
                    .replace('I', 'i')
                    .replace('İ', 'i')
                    .replace('Ö', 'o')
                    .replace('Ç', 'c')
                    .replace('ğ', 'g')
                    .replace('ü', 'u')
                    .replace('ş', 's')
                    .replace('ı', 'i')
                    .replace('ö', 'o')
                    .replace('ç', 'c')
                    .replace('ç', 'c');
            }else if(value!=null){


            }

        },
        // slugText: (value) => {
        //     value = value.toLowerCase(); // convert to lower case
        //     value = value.replace(/ /g, "-"); // replace spaces to - in input value
        //     value = self.turkishToEnglish(value);
        //     do {
        //         value = value.replace("--", "-"); // replace spaces to - in input value
        //     } while (value.includes('--'));
        //     return value;
        // },
        // setSlugValue() {
        //     alert(self.element.value)
        //     self.element.setAttribute('value',self.slugText("Asd  ad'ASd'asda'sda'sd "))
        // }
    };
    return self;
}



// Submit Form Functions
//=======================================================================
function submitForm(_url, _formId = null, _data = null) {
    if (_formId == null) _formId = '#my_form'
    let modal_id = '#MyModal';
    let table_id = '#_table';
    if (_data == null) _data = $(_formId).serialize();
    else _data = new FormData(_formId);
    let id = $(_formId + ' input[name=id]').val();
    var url = _url;
    let buttunValue = $('input[name=button_action]').val();
    let method = 'POST';
    if (buttunValue === 'update') {
        method = 'PUT';
        url = _url + "/" + id;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: url,
        method: method,
        data: _data,
        dataType: "json",
        success: function (data) {
            if (data.error.length > 0) {
                showAlertMessages($('#form_output'), data.error);
                return false;
            } else {
                $(table_id).DataTable().ajax.reload();
                $('#form_output').html('');
                showSwalMessage(data.success);
                hiddenForm();
                return true;
            }
        }
    });
}

// Delete Item Functions
function deleteItem(_url, id, redirect = null, reloadTableNames = null, _title = '', _text = null, _type = null) {
    if (_text == null) _text = "Are you sure to delete this record? ";
    if (_type == null) _type = 'warning';
    Swal.fire({
        title: _title,
        text: _text,
        type: _type,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "confirm",
        cancelButtonText: "cancel",
        closeOnConfirm: false
    }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: _url + '/' + id,
                    type: "delete",
                    dataType: "json",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        reloadTable('', reloadTableNames);
                        showSwalMessage("Has been deleted successfully");
                        if (redirect != null) window.location.href = redirect;
                        return true;
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire({
                            text: "Something went wrong ",
                            type: 'error',
                            title: thrownError,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
            return false;
        }
    );
}

//=======================================================================

// Restore Item Functions
function restoreItem(_url, id, redirect = null, reloadTableNames = null, _title = '', _text = null, _type = null) {
    if (_title == '') _title = "Restore a record";
    if (_text == null) _text = "Are you sure you want to restore this record? ";
    if (_type == null) _type = 'warning';
    Swal.fire({
        title: _title,
        text: _text,
        type: _type,
        showCancelButton: true,
        confirmButtonColor: "#117a8b",
        confirmButtonText: "Confirm restore",
        cancelButtonText: "cancel",
        closeOnConfirm: false
    }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: _url + '/restore/' + id,
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        reloadTable('', reloadTableNames);
                        showSwalMessage("Has been restored successfully !");
                        if (redirect != null) window.location.href = redirect;
                        return true;
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire({
                            text: "Something went wrong ",
                            type: 'error',
                            title: thrownError,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
            return false;
        }
    );
}

//=======================================================================

// ForceDelete Item Functions
function forceDeleteItem(_url, id, redirect = null, reloadTableNames = null, _title = '', _text = null, _type = null) {
    if (_title == '') _title = "Delete forever";
    if (_text == null) _text = "Are you sure you want to permanently delete this record? ";
    if (_type == null) _type = 'warning';
    Swal.fire({
        title: _title,
        text: _text,
        type: _type,
        showCancelButton: true,
        confirmButtonColor: "#171a1d",
        confirmButtonText: "Confirm deletion",
        cancelButtonText: "cancel",
        closeOnConfirm: true
    }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: _url + '/force-delete/' + id,
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        reloadTable('', reloadTableNames);
                        showSwalMessage("Has been force deleted successfully !");
                        if (redirect != null) window.location.href = redirect;
                        return true;
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire({
                            text: "Something went wrong ",
                            type: 'error',
                            title: thrownError,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
            return false;
        }
    );
}

//=======================================================================

// clear form

function hiddenForm(title = null, modalId) {
    if (modalId == null) modalId = '#MyModal';
    resetForm(title, modalId);
    $(modalId).modal('hide');
}

function openForm(title = null, modalId = null, _formId = null) {
    if (modalId == null) modalId = '#MyModal';
    resetForm(title, modalId, _formId);
    $(modalId).modal('show');
}


function resetForm(title = null, modalId = null, _formId = null,) {
    if (title == null) title = showAsTitle('');
    if (modalId == null) modalId = '#MyModal';
    if (_formId == null) _formId = '#my_form';
    clearForm(modalId, _formId);
    $(_formId + ' #button_action').val('insert');
    $(modalId + ' .modal-title').html('<i class="fas fa-fw fa-plus"></i> ' + title);
}

function clearForm(modalId = null, _formId = null) {
    if (modalId == null) modalId = '#MyModal';
    if (_formId == null) _formId = '#my_form';
    $(modalId).modal('hide');
    $(_formId)[0].reset();
    $(_formId + ' .form_output').html('');
    $('.has-error').removeClass('has-error');
    $('.help-validation-block').text('').attr('style', 'display:none');
}


// reload table
function reloadTable(tableName = '', reloadTableNames = null) {
    if (reloadTableNames) {
        reloadTableNames.forEach(function (item) {
            $(item).DataTable().ajax.reload();
        });
    }
    $('#' + tableName + '_table').DataTable().ajax.reload();
}

function showAsTitle(sentence = null, character = ' ') {
    if (sentence)
        return sentence.replace(/[/\\?%*:|"<>\-_]/g, character);
    else return sentence;
}

function showSwalMessage($message = "done successfully !", $title = '', $type = 'success', $timer = 1500, $position = "top-left", $showConfirmButton = false) {
    Swal.fire({
        // position: $position,
        type: $type,
        title: $title,
        text: $message,
        showConfirmButton: $showConfirmButton,
        timer: $timer
    });
}

// show Error messages
function showAlertMessages(item, data, bg_color = 'danger', $icon = "fas fa-exclamation-circle") {
    if (data.length > 0) {
        var _html = '';
        for (var count = 0; count < data.length; count++) {
            _html += '<div class="alert alert-' + bg_color + '" style="margin-bottom: 5px !important;"><i class="' + $icon + '"></i> ' + data[count] + '</div>';
        }
        $(item).html(_html);
    }
}

// show Error messages
function showOneAlertMessages(item, _symbol, data = null, bg_color = 'danger') {
    if (data == null) data = [];
    if (data.length == 0) bg_color = 'success';
    var _html = '<div class="my-3 alert alert-' + bg_color + ' displayed-data-' + _symbol + '">';
    _html += "<span class='pull-left'><a href='javascript:void(0)' id='refresh-data' class='refresh-data' data-symbol='" + _symbol + "'> فحص الروابط  <i class='fas fa-sync-alt'></i> </a></span>";
    if (data.length > 0) {
        for (var count = 0; count < data.length; count++) {
            _html += "<p><a href='" + data[count] + "' target='_blank'>" + data[count] + "</a></p>";
        }
    } else {
        _html += "<p>جميع الراوبط في هذا المحتوى صحيحة </p>";
    }
    _html += '</div>';
    $(item).html(_html);
}

// add new option to select list
function newSelectOption(item, text = '- None -', value = null, defaultSelect = false, selected = false) {
    var newOption = new Option(text, id, defaultSelect, selected);
    $(item).append(newOption).trigger('change');
}


// Bluck Delete Item Functions
//=======================================================================

function bulkDelete(resource, id, _token, _tableId = '#_table') {
    if (_token == null) _token = $('meta[name="csrf-token"]').attr('content');
    if (id.length > 0) {
        Swal.fire({
            title: "",
            text: "Are you sure to delete these records?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "تأكيد",
            cancelButtonText: "إغلاق",
            closeOnConfirm: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "/admin/" + resource + "/bulkDelete",
                    method: "POST",
                    data: {
                        id: id,
                        modelName: resource,
                        _token: _token
                    },
                    success: function (data) {
                        $(_tableId).DataTable().ajax.reload();
                        showSwalMessage("Has been deleted successfully");
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        showSwalMessage("Something went wrong !", '', 'error');
                    }
                });
            }
        });
    } else {
        toastr.error("Please select at least one checkbox");
    }
}

function bulkRestore(resource, id, _token, _tableId = '#_table') {
    if (_token == null) _token = $('meta[name="csrf-token"]').attr('content');
    if (id.length > 0) {
        Swal.fire({
            title: "",
            text: "Are you sure you want to restore these records?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "تأكيد",
            cancelButtonText: "إغلاق",
            closeOnConfirm: false
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "/admin/" + resource + "/bulkRestore",
                    method: "POST",
                    data: {
                        id: id,
                        modelName: resource,
                        _token: _token
                    },
                    success: function (data) {
                        $(_tableId).DataTable().ajax.reload();
                        showSwalMessage("Restored successfully !");
                    }, error: function (xhr, ajaxOptions, thrownError) {
                        showSwalMessage("Something went wrong !", '', 'error');
                    }
                });
            }
        });
    } else {
        toastr.error("Please select at least one checkbox");
    }
}


function GetDatatable(_url, _columns = null, _data = null, _id = null, _order = null) {
    if (_id == null) _id = '#_table';
    if (_order == null) _order = [0, 'desc'];
    if (_columns == null) _columns = [
        {data: "id", name: "id", width: "60", className: 'align-middle text-center'},
        {data: "image", name: "image", width: "100px", orderable: false, searchable: false},
        {data: "title", name: "title", width: "350"},
        {data: "action", name: "action", width: "150", orderable: false, searchable: false}
    ];
    $(_id).DataTable({
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[5, 10, 25, 50, 75, 100, 200, 500, -1], [5, 10, 25, 50, 75, 100, 200, 500, "All"]],
        pageLength: 10,
        "language": {
            // "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Arabic.json"
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/English.json"
        },
        "ajax": _url,
        columns: _columns,
        order: _order,
        data: _data,
        "drawCallback": function () {
            $('[data-toggle="tooltip"]').tooltip();
            // $("a.grouped_elements").fancybox();
        }
    });
}

function available_languages() {
    return [
        'ar',
        'en',
        'tr',
        'fa'
    ];
}

function tinymceInit(modelName, $height = 250, _token = null) {
    if (_token == null) _token = $('meta[name="csrf-token"]').attr('content');
    _asset_url = "/uploads/" + modelName;
    _templates_url = '/admin/templates';
    _images_upload_url = '/' + modelName + '/upload_from_tiny';
    _post_url = '/admin/' + modelName + '/upload_from_tiny';
    available_languages().forEach(function (_symbol) {
        var _dir = 'ltr';
        if (_symbol == 'ar' || _symbol == 'fa') {
            _dir = 'rtl';
        }
        tinymce.init({
            selector: '.editor_' + _symbol,
            directionality: _dir,
            language: 'ar',
            entity_encoding: "raw",
            font_formats: '',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace directionality visualchars fullscreen",
                "insertdatetime image link media codesample paste template table  contextmenu emoticons ",
                "hr pagebreak nonbreaking toc advlist  wordcount  imagetools textpattern help code quickbars"
            ],
            // plugins: 'print preview powerpaste searchreplace autolink directionality  visualblocks visualchars fullscreen image link media template codesample table charmap ',
            toolbar: 'fullscreen undo redo | styleselect fontsizeselect| emoticons bold italic strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify lineheightselect | numlist bullist outdent indent | removeformat | selectall cut copy  paste | rtl ltr |searchreplace preview code quickbars',
            content_css: [
                'https://fonts.googleapis.com/css?family=Poppins&display=swap',
                '//www.tiny.cloud/css/codepen.min.css',
            ],
            importcss_append: true,
            height: $height,
            relative_urls: false,
            extended_valid_elements: "*[*]",
            forced_root_block: false,
            image_advtab: true,
            table_responsive_width: true,
            fontsize: "14px",
            fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
            lineheight_formats: "8px 9px 10px 11px 12px 14px 16px 18px 20px 22px 24px 26px 36px",
            templates: _templates_url,
            file_picker_types: 'image',
            image_caption: true,
            image_title: 'test',
            images_upload_url: _images_upload_url,
            images_upload_base_path: _asset_url,
            toc_depth: 4,
            toc_header: "div",
            toc_class: "mce-toc card",
            images_upload_handler: function (blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', _post_url);
                xhr.setRequestHeader('_token', _token); // manually set header
                xhr.onload = function () {
                    var json;
                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('_token', _token);
                xhr.send(formData);
            },
        });
    })
}

function ActionButtonClickValidations(_array_of_items = null) {
    let $inValid = false;
    var $arTabError = false;
    var $enTabError = false;
    if (_array_of_items != null) {
        _array_of_items.forEach(function (element) {
            if (!Validatation(element, 'required', '', '')) {
                $inValid = true;
                // check if element is arabic
                if (element.includes("_ar")) {
                    $arTabError = true;
                }
                if (element.includes("_en")) {
                    $enTabError = true;
                } else {
                    $arTabError = true;
                }
            }
        });
    }
    if ($arTabError)
        $('.nav-pills a[href="#custom-tabs-arabic"]').tab('show');
    else if ($enTabError)
        $('.nav-pills a[href="#custom-tabs-english"]').tab('show');

    if ($inValid)
        $('.help-validation-block').attr('style', 'display:block');
    else
        $('.help-validation-block').attr('style', 'display:none');
    return $inValid;
}


var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


function lock_page_by_model(_modelName, _id) {
    $.ajax({
        url: "/admin/" + _modelName + "/refresh_locked",
        method: "POST",
        dataType: "json",
        data: {
            id: _id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
        }, error: function (data) {
            console.log('error');
        }
    })
}

function check_invalid_text($this, tinyMCE_Id = null) {
    let _symbol = $this.data('symbol');
    if (tinyMCE_Id == null) tinyMCE_Id = 'content_' + _symbol;
    let _text = tinyMCE.get(tinyMCE_Id).getContent();
    $.ajax({
        url: "/admin/check_invalid_text",
        method: "POST",
        dataType: "json",
        data: {
            text: _text,
            symbol: _symbol,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            // setting a timeout
            $('.displayed-data-' + _symbol).html('<div class="spinner-border text-light" role="status"><span class="sr-only"> Loading... </span></div>');
        },
        success: function (data) {
            showOneAlertMessages("#invalid-urls-" + _symbol, _symbol, data);
        }, error: function (data) {
            showOneAlertMessages("#invalid-urls-" + _symbol, _symbol, ['an error append on check items']);
        },
        complete: function () {
        }
    });
}

function check_content(_modelName, _id) {
    $.ajax({
        url: "/admin/" + _modelName + "/check_links_in_content",
        method: "POST",
        dataType: "json",
        data: {
            id: _id,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('.displayed-data-ar').html('<div class="spinner-border text-light" role="status"><span class="sr-only"> Loading... </span></div>');
        },
        success: function (data) {
            data.forEach(function (element) {
                showOneAlertMessages("#invalid-urls-" + element.title, element.title, element.data);
            });
        }, error: function (data) {
            showOneAlertMessages("#invalid-urls-ar", 'ar', ['an error append on check items']);
        },
        complete: function () {
            // showOneAlertMessages("#invalid-urls-ar", 'ar', []);
        }
    });
}

function SubmitOrShowError(_inValid, _formId = null) {
    if (_formId == null) _formId = '#my_form';
    if (_inValid) {
        $('.help-validation-block').attr('style', 'display:block');
        return false;
    } else {
        $('.help-validation-block').attr('style', 'display:none');
        $(_formId).submit();
    }
}

// preview image
function PreviewImage(input, _showId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(_showId).attr('src', e.target.result);

        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $(_showId).attr('src', '');
    }
}

// preview image
function jsLangSymbols() {
    var arr = [
        'ar',
        'en',
        'tr',
        'fa',
    ]
    return arr;
}

function setSlugValue(_val, _inputId) {
    let new_text = getSlugText(_val);
    $(_inputId).val(new_text);
}

function slugInput(_input) {
    let val = $(_input).val();
    let new_text = getSlugText(val);
    $(_input).val(new_text);
}

function getSlugText(val) {
    val = val.toLowerCase(); // convert to lower case
    val = val.replace(/ /g, "-"); // replace spaces to - in input value
    val = turkishtoEnglish(val);
    do {
        val = val.replace("--", "-"); // replace spaces to - in input value
    } while (val.includes('--'));
    return val;
}

function turkishtoEnglish(val) {
    return val.replace('Ğ', 'g')
        .replace('Ü', 'u')
        .replace('Ş', 's')
        .replace('I', 'i')
        .replace('İ', 'i')
        .replace('Ö', 'o')
        .replace('Ç', 'c')
        .replace('ğ', 'g')
        .replace('ü', 'u')
        .replace('ş', 's')
        .replace('ı', 'i')
        .replace('ö', 'o')
        .replace('ç', 'c');
};
// $(document).ready(function () {
//     $('[data-toggle="tooltip"]').tooltip();
//
//     //select 2
//     $(".select2").select2({
//         width: '100%'
//     });
//     //=======================================================================
//     //fancy box
//     $("a.grouped_elements").fancybox();
//     //=======================================================================
//     // Clipboard
//     var clipboard = new ClipboardJS('.btn-clipboard');
//
//     clipboard.on('success', function (e) {
//         setTooltip(e.trigger, 'Copied!');
//         hideTooltip(e.trigger);
//     });
//
//     clipboard.on('error', function (e) {
//         setTooltip(e.trigger, 'Failed!');
//         hideTooltip(e.trigger);
//     });
//
//     function setTooltip(btn, message) {
//         $(btn).tooltip('hide')
//             .attr('data-original-title', message)
//             .tooltip('show');
//     }
//
//     function hideTooltip(btn) {
//         setTimeout(function () {
//             $(btn).tooltip('hide');
//         }, 1000);
//     }
// });


