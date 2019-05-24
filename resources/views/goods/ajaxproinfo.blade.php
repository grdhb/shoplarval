@foreach ($data as $v)
<div class='b'>
      <p>用户评论</p>
      <table>
            <tr>
                  <td>{{$v->name}}|</td>
                  <td>几星|</td>
                  <td>时间:</td>
            </tr>
            <tr>
                  <td>{{$v->nr}}</td>
                  <td>{{$v->is_pj}}星</td>

                  <td style="float:right">{{$v->created_at}}</td>

            </tr>
      </table>
</div>
@endforeach
{{ $data->appends(['data'=>$data])->links() }}