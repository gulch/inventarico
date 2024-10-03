<table id="overviews" class="ui compact celled definition table">
    <thead>
    <tr>
        <th></th>
        <th>{{ trans('app.title') }}*</th>
        <th>{{ trans('app.description') }}</th>
        <th>{{ trans('app.value') }}*</th>
        <th></th>
    </tr>
    </thead>
    <tbody class="sortable">
    @if(isset($overviews) && $overviews = json_decode($overviews, true))
        <?php
            usort($overviews, function ($a, $b) {
                return $a['order'] <=> $b['order'];
            });
        ?>
        @foreach($overviews as $overview)
            <tr>
                <td></td>
                <td>
                    <input type="text" name="o_title[]" value="{{ $overview['title'] }}">
                </td>
                <td>
                    <input type="text" name="o_description[]" value="{{ $overview['description'] }}">
                </td>
                <td>
                    <input type="text" name="o_value[]" value="{{ $overview['value'] }}">
                </td>
                <td class="collapsing">
                    <a class="ui button remove_overview_btn">
                        <i class="remove icon"></i> {{ trans('app.do_remove') }}
                    </a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
    <tfoot class="full-width">
    <tr>
        <th colspan="5">
            <div id="add_overview_btn" class="ui right floated small positive labeled icon button">
                <i class="plus icon"></i> {{ trans('app.do_add_field') }}
            </div>
        </th>
    </tr>
    </tfoot>
</table>

<script src="/assets/vendor/tablednd/0.9.1/jquery.tablednd.min.js"></script>

<script>
    $(document).ready(function () {
        bindOverviewRemove();
        bindOverviewSort();

        $('#add_overview_btn').click(function () {
            $(this).closest('table').find('tbody').append('<tr><td></td><td> <input type="text" name="o_title[]"></td><td><input type="text" name="o_description[]"></td><td><input type="text" name="o_value[]"></td><td class="collapsing"><a class="ui button remove_overview_btn"><i class="remove icon"></i> {{ trans('app.do_remove') }}</a> </td></tr>');
            bindOverviewRemove();
            bindOverviewSort();
        });

        function bindOverviewSort() {
            $('#overviews').tableDnD({
                onDragClass: "warning"
            });
        }

        function bindOverviewRemove() {
            $('.remove_overview_btn').click(function () {
                $(this).closest('tr').remove();
            });
        }
    });
</script>
