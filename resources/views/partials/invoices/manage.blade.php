{!! Form::open(['action' => 'InvoicesController@postManageItems','role'=>"form"]) !!}
{!! Form::hidden('invoice_id',$invoice_id) !!}
<div id="expand-{{ $item_id }}" class="modal fade" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Item Update</h4>
			</div>
			<div class="modal-body clearfix">
	
			</div>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Color</th>
                            <th>Memo</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($items) > 0)
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['item'] }}</td>
                                <td>{{ $item['color'] }}</td>
                                <td>{{ $item['memo'] }}</td>
                                <td><input name="item[{{ $item['id'] }}]" class="item_value" type="text" old="{{ money_format('%i',$item['subtotal']) }}" value="{{ $item['subtotal'] }}"/></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
<!--                         <tr>
                            <td  colspan="4" style="text-align:right;">Total Subtotal </td>
                            <th><input id="subtotal-{{ $item_id }}" class="subtotals" type="text" value="{{ money_format('%i',$subtotal) }}"/></th>
                        </tr> -->
                    </tfoot>
                </table>

            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success btn-lg" >Update</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{!! Form::close() !!}