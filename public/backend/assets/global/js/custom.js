/**
 * custom script
 * @author ivan lubis
 * @version 2.0
 * @description this library is required jquery and other library
 */

/**
 * Convert string to url friendly.
 * 
 * @param  {String} val
 * 
 * @return {String} converted value
 */
function convert_to_uri(val)
{
    return val
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}

/**
 * List of DataTables.
 * 
 * @param  {Object} element
 * @param  {String} url
 * 
 * @return {Object} List Data
 */
function list_dataTables(element) {
    $(document).ready(function () {
        var $selected = [],
            $sort = [],
            $url = (typeof $(element).data('url') === 'undefined') ? false : $(element).data('url'),
            $limit = (typeof $(element).data('limit') !== 'undefined') ? $(element).data('limit') : 10;
        if ($url == false) {
            return;
        }
        if ($(element+ ' thead th.default_sort').index(element+ ' thead th') > 0) {
            $sort.push(
                [
                    $(element+' thead th.default_sort').index(element+ ' thead th'), 
                    'desc'
                ]
            );
        }
        var $columns = [];
        var $i = 0;
        $(element+ ' thead th').each(function() {
            $columns[$i] = {
                'data': (typeof $(this).data('name') === 'undefined') ? null : $(this).data('name'),
                'name': (typeof $(this).data('name') === 'undefined') ? null : $(this).data('name'),
                'searchable': (typeof $(this).data('searchable') === 'undefined') ? true : $(this).data('searchable'),
                'sortable': (typeof $(this).data('orderable') === 'undefined') ? true : $(this).data('searchable'),
                'className': (typeof $(this).data('classname') === 'undefined') ? null : $(this).data('classname')
            };
            $i++;
        });

        // footer element
        $(element+ ' tfoot th.searchable').each( function () {
            var $title = $(this).text();
            var $option_data = $(this).data('option-list');
            if (typeof $option_data !== 'undefined') {
                var $opt_html = '';
                $opt_html += '<select class="form-control input-sm column-option-filter">';
                $opt_html += '<option value=""></option>';

                $.each(option_data, function(value, text) {
                    $opt_html += '<option value="'+ value +'">'+ text +'</option>';
                });
                $opt_html += '</select>';
                $(this).html($opt_html);
            } else {
                $(this).html( '<input type="text" placeholder="Search '+ $title+ '" class="form-control input-sm column-search-filter" />' );
            }
        } );
        var $DTTable = $(element).DataTable({
            'processing': true,
            'serverSide': true,
            'pageLength': $limit,
            'ajax': {
                'url': $url,
                "type": "POST"
            },
            'rowCallback': function($row, $data) {
                if ( $.inArray($data.DT_RowId, $selected) !== - 1) {
                    $($row).addClass('selected');
                }
                if ( typeof $data.RowClass !== 'undefined' && $data.RowClass != '') {
                    $($row).addClass($data.RowClass);
                }
            },
            "columns": $columns,
            "order": $sort,
            "dom": '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
            responsive: true
        });
        $(element+ '_filter input').unbind();
        $(element+ '_filter input').keyup(function (e) {
            if (e.keyCode == 13) { //only on enter keypress (code 13)
                $DTTable.search(this.value).draw();
            }
        });
        if ($(element+ ' tfoot th.searchable').length > 1) {
            $DTTable.columns().every( function () {
                var $that = this;
                $( 'input', this.footer() ).on( 'keydown', function (ev) {
                     if (ev.keyCode == 13) { //only on enter keypress (code 13)
                        $that
                        .search( this.value )
                        .draw();
                    }
                } );
                $( 'select', this.footer() ).on( 'change', function (ev) {
                    $that
                    .search( this.value )
                    .draw();
                } );
            } );
        }

        // selected row
        $(element+ ' tbody').on('click', 'tr', function () {
            var $id = this.id;
            var $index = $.inArray($id, $selected);

            if ($index === -1) {
                $selected.push($id);
            } else {
                $selected.splice($index, 1);
            }
            $("#delete-record-field").val($selected);

            $(this).toggleClass('selected');
        });

        // delete record
        $(document).on('click', '.delete-record, #delete-record', function () {
            if ($selected.valueOf() != '') {
                var $this = $(this),
                    $this_html = $(this).html(),
                    $delete_url = $(this).data('url');
                if (typeof $delete_url !== 'undefined') {
                    swal({
                        title: "Are you sure?",
                        text: "You will not be able to recover this record!",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true
                    })
                    .then(function() {
                        // swal('success!!', 'berhasil', 'info');
                        $.ajax({
                            'type': 'delete',
                            'url': $delete_url,
                            'dataType': 'json',
                            'data': {
                                'id': $selected
                            },
                            beforeSend: function() {
                                $this.html('Loading...').attr('disabled', true);
                            },
                            complete: function() {
                                $this.html($this_html).removeAttr('disabled');
                            }
                        }).done(function(response) {
                            if (response['message']) {
                                swal(response['status']+ '!!', response['message'], 'info');
                            }
                            if (response['status'] == 'success') {
                                // var dataTable = $('.dataTable').DataTable();
                                $DTTable.row($this.parents('tr')).remove().draw();
                            }
                        })
                    });
                }
            }
        });
    });
}

/**
 * Ajax Post Data
 * 
 * @param  {string} url URL
 * @param  {string} data post data
 * @return {object} callback
 */
function ajax_post(url, data) {
    var callback = $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: data,
        cache: false
    });
    return callback;
}


/**
 * submit via ajax by button
 * 
 * @param {string} url
 * @param {string} data
 * @param {object} this_var
 * @returns object/var
 */
function submit_ajax(url, data, this_var) {
    var callback = $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: data,
        cache: false,
        beforeSend:function() {
            if (this_var || typeof this_var !== 'undefined') {
                this_var.html('Loading...');
                this_var.attr('disabled', true);
            }
        }
    });
    return callback;
}
