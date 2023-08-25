<!DOCTYPE html>
<html>
<head>
    
                <style type="text/css">
                   .green{
              background-color: #90ee90;
              
            }
            .red{
              background-color: #ff7675;
            }
            .table_que{
                width: 100% !important;
                border: 1px solid #ddd;
            }
            .table_que tr{
                width: 100% !important;

            }
            .table_que tr td p{
                 width: 100%  !important;
                margin-left: 15px;

            }
            .table_que tr td div{
                width: 200px !important;
                margin-left: 15px;
            }
            
    </style>
</head>
<body> 
    <div class="container">
        <table style="width: 100%">
            <th style="width: 30%;text-align: left;font-size: 25px;">{{$username}} Summary</th>
            <th style="width: 70%;margin-left: auto;display: block;text-align: right;font-size: 25px;">THINK TANKER</th>
        </table>
        <div style="width: 100%;">
            <table style="width: 100%;float: left;border: 1px solid #ddd;">
                <tbody>
                    <tr>
                        <th style="float: left;width: 22%;padding: 10px"><b>Correct Answers</b></th>
                        <th style="float: left;width: 20%;padding: 10px"><b>Wrong Answers</b></th>
                        <th style="float: left;width: 20%;padding: 10px"><b>Skipped Answers</b></th>
                    </tr>
                    <tr>
                        <td style="float: left;width: 20%;padding: 10px" align="center">
                                {{$correct}}
                        </td>
                        <td  style="width: 25%;padding: 10px;" align="center">
                                {{$wrong}}
                        </td>
                        <td  style="width: 30%;padding: 10px;" align="center">
                                {{$skip}}
                        </td>
                    </tr>   
                </tbody>
            </table>
        </div>
            <?php  $count = 1; ?>
              
                <table class="table_que" >
                    @foreach($question as $key=>$val)
                            <?php $q = 1+$key; ?>
                            <?php $correct = array_search($val['correctAnswer'], array_keys($val['choices'])); ?>
                    <tr >
                        <td>
                            <p style="font-weight: 600; margin-bottom: -10px; margin-top: 40px" for="No"  class="">{{$count++}}. {{$val['title']}}</p><br>
                        </td>
                    </tr>      
                          @foreach($val['choices'] as $keyy => $option)
                            <tr >
                                    <td  >
                                           <div  @if($val['choices'][$correct] == $option) class="green"  @elseif($option==$useranswer[$key]) class="red"  @endif >
                                            <input style="vertical-align: top;margin-left: 10px;  " type="radio"  @if($option==$useranswer[$key])checked @endif
                                             
                                            value="{{$option}}" 
                                            id="question{{$q}}" name="question{{$q}}"><span style="margin:15px;">{{$option}}</span>
                                            </div>
                                    </td> 
                            </tr>
                          @endforeach
                    @endforeach
                </table>
    </div>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</body>
</html>