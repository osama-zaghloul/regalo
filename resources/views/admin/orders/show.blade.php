@extends('admin/include/master')
@section('title') لوحة التحكم | مشاهدة تفاصيل الطلب  @endsection
@section('content')

  <section class="content-header"></section>
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> رقم الطلب   {{$showorder->order_number}}#
            <small class="pull-left">تاريخ الطلب : {{ date('Y/m/d', strtotime($showorder->created_at)) }}</small>
          </h2>
        </div>
      </div>
     
      <div class="row invoice-info">
        <div class="col-sm-12 invoice-col">
            @if($showorder->status == 0)
                <span style="border-radius: 3px;border: 1px solid green;color: orange;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">قيد الانتظار</span>
            @elseif($showorder->status == 1) 
                  <span style="border-radius: 3px;border: 1px solid green;color: springgreen;float:left;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">جارى التجهيز</span>
            @elseif($showorder->status == 2)   
                  <span style="border-radius: 3px;border: 1px solid #c22356;float:left;color:crimson;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم رفض الطلب</span>
            @elseif($showorder->status == 3)   
                  <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;" class="ads__item__featured">تم التسليم</span>
            @endif    
               
            @if($showorder->paid == 0)   
              {{ Form::open(array('method' => 'patch',"onclick"=>"return confirm('هل انت متاكد ؟!')",'files' => true,'url' =>'adminpanel/bills/'.$showorder->id )) }}
                      <input type="hidden" name="confirm" >الدفع عن طريق التحويل البنكي
                      <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i>تفعيل</button>
              {!! Form::close() !!}
            @elseif($showorder->paid == 1)   
                  {{ Form::open(array('method' => 'patch',"onclick"=>"return confirm('هل انت متاكد ؟!')",'files' => true,'url' =>'adminpanel/bills/'.$showorder->id )) }}
                      <input type="hidden" name="confirm" >الدفع عند الإستلام   
                      <button type="submit" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i>تفعيل</button>
              {!! Form::close() !!}
            @elseif($showorder->paid == 2)   
                  <span style="border-radius: 3px;border: 1px solid green;float:left;color:green;padding: 3px;font-weight: bold;background: #fff;display: inline-block;margin-top: 4%;margin-left: 5px;" class="ads__item__featured">تم الدفع</span>
            @endif 
          صاحب الطلب
          <address>
           <a href="{{asset('adminpanel/users/'.$ownerinfo->id)}}"> 
            <strong>{{$ownerinfo->name}}</strong> </a> <br>
             
             رقم الجوال : {{$ownerinfo->phone}}<br>
            
          </address>
        </div>
        
      <div class="row">
        <div class="col-xs-12">
          <div class="table-responsive">
            @foreach($itemorders as $item)
              <?php 
                  $iteminfo  = DB::table('items')->where('id',$item->item_id)->first();
                  $itemimage = DB::table('item_images')->where('item_id',$item->item_id)->value('image');
              ?>
              <div class="col-md-8">
                <table class="table">
                    <tbody>

                     
                      
                      <tr>
                          <th style="width: 25%;">كود المنتج</th>
                          <td>
                            {{$iteminfo->code != null ? $iteminfo->code : '000000'}}
                          </td>
                      </tr>
                     
                      
                      <tr>
                            <th style="width: 25%;">الكمية</th>
                            <td>{{$item->qty}}</td>
                      </tr>

                      <tr>
                            <th style="width: 25%;">السعر</th>
                            <td>{{$item->price}} ريال</td>
                      </tr>

                      

                    </tbody>
                </table>
              </div>
              <div class="col-md-4">
                  <img style="width:100%;height:110px;" src="{{asset('users/images/'.$itemimage)}}" alt="{{$iteminfo->artitle}}">
              </div>
            @endforeach
          </div>
          <div class="col-md-12">
              <h3>المكان : <span style="color:#500253">{{$showorder->place}}</span> </h3>
              <h3>التاريخ : <span style="color:#500253">{{$showorder->date}}</span> </h3>
              <h3>الوقت : <span style="color:#500253">{{$showorder->time}}</span> </h3>
              <h3>التفاصيل : <span style="color:#500253">{{$showorder->details}}</span> </h3>
              <h3>مصاريف الشحن : <span style="color:#500253">{{$showorder->shipping}}</span> ريال</h3>
              
          </div>  
          <div class="col-md-12">
              <h2>الاجمالى : <span style="color:#500253">{{$showorder->total}}</span> ريال</h2>
          </div>  
        </div>
      </div>

    </section>
@endsection