<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">   
     <title></title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style type="text/css">
        @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");
:root {
  --bgprm: #FFB400;
  --bgfont: white;
}
* {
  margin: 0;
  padding: 0;
  font-family: "Roboto", sans-serif;
  box-sizing: border-box;
}
html {
  max-width: 100%;
}
body {
  font-family: "Roboto", sans-serif;
  color: black;
}
.quizbody {
  background-color: var(--bgfont);
}
.btn {
  --c: var(--bgprm);
  color: var(--c);
  font-size: 16px;
  border: 0.2em solid var(--c);
  background: transparent;
  text-transform: uppercase;
  font-weight: bold;
  letter-spacing: 0.1em;
  text-align: center;
  position: relative;
  overflow: hidden;
  z-index: 1;
  padding: 10px 30px;
  transition: 0.5s;
}

.next {
  --c: var(--bgprm);
  color: green;
  font-size: 16px;
  border: 0.2em solid green;
}

.skip {
  --c: var(--bgprm);
  color: red;
  font-size: 16px;
  border: 0.2em solid red;
}

.btn span {
  position: absolute;
  width: 25%;
  height: 100%;
  background-color: var(--c);
  transform: translateY(150%);
  border-radius: 50%;
  left: calc((var(--n) - 1) * 25%);
  transition: 0.5s;
  transition-delay: calc((var(--n) - 1) * 0.1s);
  z-index: -1;
}

.btn:hover {
  color: var(--bgfont);
  text-align: left;
}

.btn:hover span {
  transform: translateY(0) scale(2);
}

.btn span:nth-child(1) {
  --n: 1;
}

.btn span:nth-child(2) {
  --n: 2;
}
.btn span:nth-child(3) {
  --n: 3;
}

.btn span:nth-child(4) {
  --n: 4;
}
#mainButton {
  font-size: 20px;
  padding: 16px 100px;
}
.btn:focus {
  box-shadow: none;
}
.btn:focus span {
  transform: translateY(150%);
}
.button-div {
  padding: 150px 0;
}
.bgblue {
  background-color: var(--bgprm);
}
.form_box {
  width: 540px;
  border: 0.2em solid var(--bgprm);
  border-radius: 5px;
  margin-top: 20px;
  transition: all 0.3s ease;
  color: var(--bgfont);
}

.form-list {
  padding: 15px 30px;
}

.info_box {
  width: 540px;
  background: var(--bgprm);
  border-radius: 5px;
  margin-top: 20px;
  transition: all 0.3s ease;
  color: var(--bgfont);
}

.info-title {
  height: 60px;
  width: 100%;
  border-bottom: 1px solid lightgrey;
  display: flex;
  align-items: center;
  padding: 0 30px;
  border-radius: 5px 5px 0 0;
  font-size: 25px;
  font-weight: 600;
}
.info-list {
  padding: 15px 30px;
}

.info {
  margin: 5px 0;
  font-size: 17px;
}

.info span {
  font-weight: 700;
  color: lightgray;
}
.buttons {
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 0 20px;
  border-top: 1px solid lightgrey;
}

.buttons button {
  margin: 0 5px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer !important;
  border-radius: 5px;
  transition: all 0.3s ease;
  color: var(--bgprm);
  background-color: var(--bgfont);
  padding: 10px 15px;
}
.buttons button:hover {
  color: #FFB400;
  transform: scale(1.05);
}
.info_box {
  display: none;
}

.containerr {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

[type="radio"] {
  z-index: -1;
  position: absolute;
  opacity: 0;
}

.green{
  background-color: #90ee90;
}
.red{
  background-color: #FF0000;
}
[type="radio"]:checked ~ label {
  border-color: var(--bgprm);
  background-color: rgba(97, 154, 234, 0.16);
  color: var(--bgprm);
}
[type="radio"]:checked ~ label:before {
  will-change: transform, border-width, border-color;
  -webkit-animation: bubble 1s ease-in;
  animation: bubble 1s ease-in;
}
[type="radio"]:checked ~ label:after {
  will-change: opacity, box-shadow;
  -webkit-animation: sparkles 700ms ease-in-out;
  animation: sparkles 700ms ease-in-out;
}
[type="radio"]:checked ~ label > span {
  will-change: transform;
  border: 0;
  background-image: linear-gradient(to top right, var(--bgprm), #4363ee);
  -webkit-animation: radio 400ms cubic-bezier(0.17, 0.89, 0.32, 1.49);
  animation: radio 400ms cubic-bezier(0.17, 0.89, 0.32, 1.49);
}
[type="radio"]:checked ~ label > span:after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 6px;
  height: 6px;
  border-radius: 10px;
  background-color: #fff;
}
label {
  position: relative;
  display: grid;
  align-items: center;
  grid-template-columns: 100px 10 10px;
  grid-gap: 20px;
  width: 310px;
  height: 50px;
  padding: 0 20px;
  border-radius: 30px;
  border: 1px solid var(--bgprm);
  cursor: pointer;
  background-color: transparent;
  transition: all 300ms ease-in;
}
label:hover {
  border-color: #4062f6;
  background-color: rgba(97, 154, 234, 0.16);
}
label:before,
label:after {
  position: absolute;
  left: 29px;
  border-radius: 50%;
  content: "";
}
label:before {
  margin: -2rem;
  border: solid 2rem #545461;
  width: 4rem;
  height: 4rem;
  transform: scale(0);
}
label:after {
  margin: -0.1875rem;
  width: 0.375rem;
  height: 0.375rem;
  box-shadow: 0.32476rem -2.6875rem 0 -0.1875rem #ff8080,
    -0.32476rem -2.3125rem 0 -0.1875rem #ffed80,
    2.30366rem -1.42172rem 0 -0.1875rem #ffed80,
    1.6055rem -1.69573rem 0 -0.1875rem #a4ff80,
    2.54785rem 0.91464rem 0 -0.1875rem #a4ff80,
    2.32679rem 0.19796rem 0 -0.1875rem #80ffc8,
    0.87346rem 2.56226rem 0 -0.1875rem #80ffc8,
    1.29595rem 1.94258rem 0 -0.1875rem #80c8ff,
    -1.45866rem 2.28045rem 0 -0.1875rem #80c8ff,
    -0.71076rem 2.2244rem 0 -0.1875rem #a480ff,
    -2.69238rem 0.28141rem 0 -0.1875rem #a480ff,
    -2.18226rem 0.8312rem 0 -0.1875rem #ff80ed,
    -1.89869rem -1.92954rem 0 -0.1875rem #ff80ed,
    -2.01047rem -1.18791rem 0 -0.1875rem #ff8080;
}
label > span {
  position: relative;
  display: inline-flex;
   /* border-radius: 20px;
    border: 2px solid #454861;
    background-image: linear-gradient(to bottom, #3b4059, #1c1e2d);*/
}

.option:not(:last-child) {
  margin-bottom: 4px;
}

@keyframes radio {
  0%,
  17.5% {
    transform: scale(0);
  }
}
@keyframes bubble {
  15% {
    transform: scale(1);
    border-color: #545461;
    border-width: 1rem;
  }
  30%,
  100% {
    transform: scale(1);
    border-color: #545461;
    border-width: 0;
  }
}
@keyframes sparkles {
  0%,
  10% {
    opacity: 0;
    transform: scale(0);
  }
  15% {
    opacity: 1;
    transform: scale(1.2) rotate(-20deg);
    box-shadow: 0.32476rem -2.1875rem 0 0rem #ff8080,
      -0.32476rem -1.8125rem 0 0rem #ffed80,
      1.91274rem -1.10998rem 0 0rem #ffed80,
      1.21459rem -1.38398rem 0 0rem #a4ff80,
      2.06039rem 0.80338rem 0 0rem #a4ff80, 1.83932rem 0.0867rem 0 0rem #80ffc8,
      0.65652rem 2.11178rem 0 0rem #80ffc8, 1.07901rem 1.4921rem 0 0rem #80c8ff,
      -1.24172rem 1.82996rem 0 0rem #80c8ff,
      -0.49382rem 1.77391rem 0 0rem #a480ff,
      -2.20492rem 0.17015rem 0 0rem #a480ff,
      -1.69479rem 0.71994rem 0 0rem #ff80ed,
      -1.50777rem -1.61779rem 0 0rem #ff80ed,
      -1.61955rem -0.87617rem 0 0rem #ff8080;
  }
}

.quiz_box,
.result_box {
  margin: 60px 0;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
.quiz_box,
.result_box {
  width: 700px;
  background: #fff;
  border-radius: 5px;
  transition: all 0.3s ease;
}
.quiz_box header,
.result_box header {
  position: relative;
  z-index: 2;
  height: 70px;
  padding: 0 30px;
  background: #fff;
  border-radius: 5px 5px 0 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0px 3px 5px 1px rgba(0, 0, 0, 0.1);
}
.quiz_box header .title {
  font-size: 20px;
  font-weight: 600;
}
.timer {
  color: #081e35;
  background: #FFB400;
  height: 45px;
  padding: 0 8px;
  border-radius: 30px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 145px;
}

.time_left_txt {
  font-weight: 400;
  font-size: 17px;
  user-select: none;
}
.timer_sec {
  font-size: 18px;
  font-weight: 500;
  height: 30px;
  width: 45px;
  border-radius: 30px;
  line-height: 30px;
  text-align: center;
  user-select: none;
}

.time_line {
  position: absolute;
  bottom: 0px;
  left: 0px;
  height: 3px;
  width: 100%;
  transition: width 1s linear;
  background: #FFB400;
}
.qasection {
  padding: 25px 30px 20px 30px;
  background: #fff;
  height: 420px;
}
.que_text {
  font-size: 25px;
  font-weight: 600;
  height: 140px;
}

.option_list {
  padding: 20px 0px;
}

.option_list .option:last-child {
  margin-bottom: 0px;
}
footer {
  height: 60px;
  padding: 0 30px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid lightgrey;
  padding-right: 10px;
}
.total_que span {
  user-select: none;
}

.total_que span p {
  font-weight: 500;
  padding: 0 5px;
}
.total_que span p:first-child {
  padding-left: 0px;
}
.btns button:hover {
  color: var(--bgprm);
}
.questionbody {
  display: none;
}
/* .total_que {
  display: flex;
} */
.pointerNone {
  pointer-events: none;
}
.btns > button.btn.active {
  border-color: black;
  color: black;
}
.disable {
  pointer-events: none;
  border-color: #545461;
  color: #545461;
}
.result {
  display: none;
}
section.result-showing-box {
  padding: 32px 20px;
}
.perc-line-div,
.time-line-div {
  height: 5px;
  background: aliceblue;
  border-radius: 30px;
}
.perc-line,
.time-line {
  height: 5px;
  background-color: #FFB400;
  width: 100%;
  border-radius: 29px;
}
.time-result,
.perc-result {
  margin-top: 16px;
}
p.icons-text.mb-0 {
  font-size: 23px;
  font-weight: 700;
  margin-bottom: 10px;
}
.icons i {
  font-size: 32px;
  color: cornflowerblue;
  padding-right: 10px;
  padding-top: 10px;
  cursor: pointer;
}
.icons i:hover {
  color: #FFB400;
}
.icons {
  display: flex;
  justify-content: center;
  align-items: center;
}
.resultbody {
  display: none;
}
.modal-dialog{
  max-width: 700px !important;
}
.label_child{
  width: 50%;
}


@media only screen and (max-width: 576px) {
  .container, .container-sm {
    max-width: 450px;
}
.form_box,.info_box{
  width: 100%;
}
#user,#email{
  margin-left: 0 !important ;
}
footer {
  height: auto !important ;
  padding: 10px 30px !important ;
  display: flex !important  ;
  flex-direction: column !important;
}
.quiz_box{
  width: 100%;
  margin: 20px 0;
}
.que_text{
  height: auto;
}
.qasection {
  height: auto !important ;
}
.btns{
  display: flex;
}
.btns button{
  margin: 0 7px;
  padding: 5px 15px !important  ;
} 
}

      .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
          -webkit-user-select: none; /* Safari */
           -khtml-user-select: none; /* Konqueror HTML */
             -moz-user-select: none; /* Old versions of Firefox */
              -ms-user-select: none; /* Internet Explorer/Edge */
                  user-select: none; /* Non-prefixed version, currently
                                        supported by Chrome, Edge, Opera and Firefox */
   }
    </style>
</head>

  <body>
    <!-- website section start -->
    <div class="container-fluid px-0">
    <section class="userdetails">
        <div class="container">
          <div class="row">
             <div class="col-12 d-flex text-center justify-content-center align-items-center flex-column" style="padding: 50px 0; background-color: #FFB400; color: black;">
              <h1 class="quizh1" style="font-size: 3rem; font-weight: 700;">
                THINK TANKER
              </h1>
              <h3 class="descriptionh3">
                Fill below Details Before to Start the Test
              </h3>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center flex-column">
              <div class="form_box">
                
                 <?= Form::open(array('class' => 'form-horizontal' ,'files' => true,)) ?>
                <div class="form-list" style="color:black;">
                  <div class="form-group row">
                    <div class="col-sm-9">
                        <?= Form::text('fullname',old('fullname'), ['class' => 'form-control', 'placeholder' => 'Enter Your Full Name','id'=>'user','style'=>'margin-left: 55px;']); ?>
                        <span id="nameerror" style="margin: 0px 56px;color:red;"></span>
                    </div>
                  </div>
                  <br>
                  <div class="form-group row">
                    <div class="col-sm-9">
                        <?= Form::email('email',old('email'), ['id'=>'email','class' => 'form-control','style'=>'margin-left: 55px;', 'placeholder' => 'Enter Your Email']); ?>
                        <span id="emailerror" style="margin: 0px 56px;color:red;"></span>
                      </div>
                </div>
                </div>
                <div class="buttons">
                  <button class="btn" id="submit1" style="color:black;">Continue</button>
                </div>
                {!! Form::close() !!}

              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="quizbody">
        <div class="container">
          <div class="row">
            <div class="col-12 d-flex text-center justify-content-center align-items-center flex-column" style="padding: 50px 0; background-color: #FFB400; color: black;">
              <h1 class="quizh1" style="font-size: 3rem; font-weight: 700;">
                Aptitude Test
              </h1>
              <h3 class="descriptionh3">
                Click below Button to Start the Test
              </h3>
            </div>
            <div class="col-12 button-div d-flex justify-content-center align-items-center">
              <div>
                <button class="btn" style="color:black;" id="mainButton">
                  Start Your Test
                  <span></span><span></span><span></span><span></span>
                </button>
              </div>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center flex-column">
              <div class="info_box">
                <div class="info-title" style="color:black;"><span>Some Rules of this Test</span></div>
                <div class="info-list" style="color:black;">
                  <div class="info">1. You will have only <span style="color:black;">180 seconds</span> per each question.</div>
                  <div class="info">2. You can skip any question you want.</div>
                  <div class="info">3. You can't select any option once time goes off.</div>
                  <div class="info">4. You can't exit from the Test while you're giving exam.</div>
                  <div class="info">5. You'll get points on the basis of your correct answers.</div>
                  <div class="info">6. Your skipped question will be result 0 points.</div>
                  <div class="info">7. Do Not Refresh Page During Test</div>
                </div>
                <div class="buttons">
                  <button class="quit btn" style="color:black;">Exit Test</button>
                  <button class="continue btn" style="color:black;">Continue</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="questionbody">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
              <div class="quiz_box">
                <header>
                  <div class="title">Test Application</div>
                  <div class="timer">
                    <div class="time_left_txt" style="background-color: #FFB400;">Time Left</div>
                    <div class="timer_sec">180</div>
                  </div>
                  <div class="time_line"></div>
                </header>
                <section class="qasection" style="height: fit-content;">
                  <div class="que_text noselect">
                    How old am I?
                  </div>
                  <div class="option_list containerr">
                    <div class="option">
                      <input type="radio" class="option" name="option" id="opt1" value="opt1input">
                      <label for="opt1" class="noselect" aria-label="opt1" style="height: fit-content;">
                        <span></span>
                        option 1
                      </label>
                      </input>
                    </div>

                    <div class="option">
                      <input type="radio" class="option" name="option" id="opt2" value="opt1input">
                      <label for="opt2" class="noselect" aria-label="opt2" style="height: fit-content;">
                        <span></span>
                        option 2
                      </label>
                    </div>

                    <div class="option">
                      <input type="radio" class="option" name="option" id="opt3" value="opt1input">
                      <label for="opt3" class="noselect" aria-label="opt3" style="height: fit-content;">
                        <span></span>
                        option 3
                      </label>
                    </div>
                    <div class="option">
                      <input type="radio" class="option" name="option" id="opt4" value="opt1input">
                      <label for="opt4" class="noselect" aria-label="opt4" style="height: fit-content;">
                        <span></span>
                        option 4
                      </label>
                    </div>
                  </div>
                </section>

                <footer>
                  <div class="total_que">
                    <span class="QNO"></span> of 10
                  </div>
                  <div class="btns">
                    <button class="back btn">back</button>
                    <button class="btn skip">Skip</button>
                    <button class="btn next">Next</button>
                    <button class="btn result">Result</button>
                  </div>
                </footer>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="resultbody">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
              <div class="result_box">
                <header class="d-flex flex-column">
                  <div class="title" style="
                                  margin-top: 10px;
                                  font-size: 30px;
                                  font-weight: 700;
                                  color: black;
                              ">Your Result</div>
                  <div class="time_line"></div>
                </header>
                <section class="result-showing-box">
                  <div class="answer-info">
                    <div class="total-ans d-flex justify-content-between">
                      <h3>ALL Questions</h3>
                      <h3 style="color: black;">10</h3>
                    </div>
                    <div class="correct-ans d-flex justify-content-between">
                      <h4>Correct Answers</h4>
                      <h4 class="Correct-ans-given" style="color: black;"></h4>
                    </div>
                    <div class="wrong-ans d-flex justify-content-between">
                      <h4>Wrong Answers</h4>
                      <h4 class="wrong-ans-given" style="color: black;"></h4>
                    </div>
                    <div class="skipped-ans d-flex justify-content-between">
                      <h4>Skipped Answers</h4>
                      <h4 style="color: black;" class="skip-ans-given"></h4>
                    </div>
                  </div>
                  <div class="perc-result">
                    <h3>Your Percentage is <span class="percentage" style="color: black;"></span></h3>
                    <div class="perc-line-div">
                      <div class="perc-line"></div>
                    </div>
                  </div>
                  <div class="time-result">
                    <h3>You completed the Test in <span class="time" style="color: black;"></span> Seconds</h3>
                    <div class="time-line-div">
                      <div class="time-line"></div>
                    </div>
                  </div>
                </section>
                <footer style="height: 100px;">
                  <div class="btns">
                    <button class="pdf btn">Generate PDF</button>
                    <button class="btn span" style="color:black;">You Are Eligible For Next Round</button>

                  </div>
                </footer>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="modal" id="resultpage" role="dialog" tabindex="-1" aria-labelledby="leave-detailsLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Result</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="{{  url('/download',$token) }}"><button class="btn btn-secondary">Download</button></a>
      </div>
    </div>
  </div>
</div>
    
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery.session@1.0.0/jquery.session.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">

  
      var quizarray = <?= json_encode($question); ?>;
      quizarray=JSON.stringify(quizarray);
      const quiz=JSON.parse(quizarray);
      
      var token = <?= json_encode($token); ?>;
</script>
  <script>
        $(document).ready(function () {

var questionnumber=-1;
let scndsLftOfQueArr=[];
let radioBtnChecked=[];

// Some work for DOM Manipulation start
    $(".quizbody").hide();

    
     $("#submit1").click(function(e){
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var namereg = /^[a-zA-Z ]+$/;
        e.preventDefault();
        $('#nameerror').text('');
            $('#emailerror').text('');
        var name = $.trim($('#user').val());
        var emailname = $.trim($('#email').val());
        var error = false;
       
            if(name == ''){
                $('#nameerror').text('Please enter name');  
                error = true;  
            }
            else if(!namereg.test(name)){
                $('#nameerror').text('Use Only Alphabatic character');
                error = true;
            }

            if(emailname == ''){
                $('#emailerror').text('Please enter email');
                error = true;
            }
            else if(!emailReg.test(emailname)){
                $('#emailerror').text('Enter Valid Email Adrees');
                error = true;
            }
            
        if (error == false){
            $(".quizbody").show();
            $(".userdetails").hide();    
        }
        
       
   });
     var user;
     var email;
     function username() {
           $("#submit1").click(function(){
                 user = $('#user').val();
                 email = $('#email').val();
           });
     }

     username(); 

    
    $("#mainButton").click(function(){
        $(".quizh1").text("Rules");
        $(".descriptionh3").text("Read the Rules and understand them.");
        $(this).parent().fadeIn();
        $(this).parent().parent().remove();
        $(".info_box").fadeIn();
   });


   $(".quit").click(function(){
    location.reload();
   });

   $(".continue").click(function(){
    $(".quizbody").slideUp(1000);
    $(".questionbody").fadeIn(1000);
    questionnumber++;
    countTotalTime();
    showquestionnum();
    showquestion();
    diablingButtons();
    saveRadioBtnValue();
   });

   $('.btn').on('mouseenter', function () {
    $(this).addClass('active');
   });
   $('.btn').on('mouseleave', function () {
    $(this).removeClass('active');
   });
   function diablingButtons() {
       if (questionnumber==0) {
         $(".back").addClass('disable');  
       }
       else
       {
         $(".back").removeClass('disable');  
       }
   }
// Some work for DOM Manipulation end

//allowing uncheck the radio button -->
        document.querySelectorAll('input[type=radio][name=option]').forEach((elem) => {
            elem.addEventListener('click', allowUncheck);
            elem.previous = elem.checked;
        });
     
    function allowUncheck(e) {
    if (this.previous) {
        this.checked = false;
    }
    document.querySelectorAll(
        `input[type=radio][name=${this.name}]`).forEach((elem) => {
        elem.previous = elem.checked;
    });
    };
//allowing uncheck the radio button <--

// starting the quiz's logical work
   
   let randomnumber;
   let randomnumarr=[];
   let allquestion=[];
   let indexpre=randomnumarr[questionnumber];
   let index =quiz[indexpre];

    //console.log(randomnumarr,"randarray")

   //getting the random number function -->
        function getrandomnumber(){
        randomnumber=Math.floor(Math.random()*(quiz.length));
        };
   //getting the random number function <--
     //checking the Random Number for not getting same number -->
     var number = (quiz.length)+1;
    // console.log(number,"number")   
     function checkRandomNumber() {
         for (let i = 0; i < number; i++) {     
                getrandomnumber()
                let checkRN= jQuery.inArray( randomnumber,  randomnumarr);
                if (checkRN==-1) {
                    randomnumarr[questionnumber]=randomnumber;
                    break;
                }
                }
                indexpre=randomnumarr[questionnumber];
                index =quiz[indexpre];
                allquestion.push(index);
     }        

    //checking the Random Number for not getting same number <--

    //showing the QUESTIONS function -->
     function showquestion() {
         radioButtons = $("input:radio[name='option']");
         if (questionnumber<randomnumarr.length) {
             indexpre=randomnumarr[questionnumber];
             index =quiz[indexpre]

            $(".que_text").text(index.title);
            $("label").eq(0).text(index.choices[0]);
            $("label").eq(1).text(index.choices[1]);
            $("label").eq(2).text(index.choices[2]);
            $("label").eq(3).text(index.choices[3]);
            for (var x = 0; x < radioButtons.length; x++) {
                var idVal = $(radioButtons[x]).attr("id");
                radioBtnCheckedVal=$("label[for='"+idVal+"']").text();
                if (radioBtnCheckedVal === radioBtnChecked[questionnumber]) {
                    radioButtons[x].checked = true;
                }
                if (radioBtnChecked[questionnumber]===" ") {
                    radioButtons[x].checked = false;
                }
            }
            if (questionnumber>0) {
                 resetingTheTime();
             }
             startTimeLeft();
        }
        else
        {
             checkRandomNumber();
            $(".que_text").text(index.title);
            $("label").eq(0).text(index.choices[0]);
            $("label").eq(1).text(index.choices[1]);
            $("label").eq(2).text(index.choices[2]);
            $("label").eq(3).text(index.choices[3]);
            $("input:radio[name='option']").each(function(i) {
                this.checked = false;
            }); 
            if (questionnumber>0) {
                 resetingTheTime();
             }
             startTimeLeft();
        } 
     };
     //showing the QUESTIONS function <--

    //  starting the time of question start function-->


    let secondSetInterval;
    let width;
    function startTimeLeft() {
      secondSetInterval = setInterval(function () {
          
          index.secondsLeft-=1;
          width=(index.secondsLeft/20)*100;
       
            if (index.secondsLeft<10) {
              $(".timer_sec").text("0" + index.secondsLeft);
            }
            else
            {
                $(".timer_sec").text(index.secondsLeft); 
            }
            if (index.secondsLeft==0) {
            $(".option_list").addClass("pointerNone");
            index.pointerEvents=true;
            $(".quiz_box").prepend(`<marquee id="marquee" class="marquee my-2" width="100%" direction="right" height="20px">
            You cannot select any option Now.
            </marquee>`);
            clearInterval(secondSetInterval);
            }
      },1000);
    };
    //  starting the time of question end function <--

    //  resetting the time of question start function -->
    function resetingTheTime() {
      clearInterval(secondSetInterval);
      secondsForTimeOut=index.secondsLeft;
     scndsLftOfQueArr[questionnumber] = index.secondsLeft;
      secondCount = index.secondsLeft;
      $(".timer_sec").text(index.secondsLeft);    
    };
    //  resetting  the time of question end function <--

    //  starting the time of question start function-->
    let totalSetInterval;
    let totaltime=0;
    function countTotalTime() {
      totalSetInterval = setInterval(function () {
          totaltime+=1;
      },1000);
    };
    //  starting the time of question end function <--

    //  calculating the score and storing the checked values in-->
    let radioBtnCheckedVal;
    function saveRadioBtnValue() {
        $("input:radio[name='option']").each(function(i){
            if($(this).is(':checked'))
            {
                var idVal = $(this).attr("id");
                radioBtnCheckedVal=$("label[for='"+idVal+"']").text();
                return false;
            }
            else{
                radioBtnCheckedVal=" "
            }
        });
          var userAns = radioBtnCheckedVal;
        radioBtnChecked[questionnumber] = userAns;
    }

    // calculating the score <--

     //showing the QUESTIONS Number function -->
     function showquestionnum() {
          $(".QNO").text(questionnumber+1 + " ");
     }
     //showing the QUESTIONS Number function <--

// ending the quiz's logical work

// adding the functionalities to buttons starts

$(".back").click(function(){
        if (questionnumber<10 && questionnumber>=1) {
            $(".result").hide();
            $(".next").show();
            $(".skip").removeClass('disable');
            $("#marquee").remove();
            saveRadioBtnValue();
            clearInterval(secondSetInterval);
            questionnumber--;
            showquestionnum();
            showquestion();
            diablingButtons();
            if (index.pointerEvents===true) {   
              $(".option_list").addClass("pointerNone");
               $(".quiz_box").prepend(`<marquee id="marquee" class="marquee my-2" width="100%" direction="right" height="20px">
                You cannot select any option Now.
                </marquee>`)
                clearInterval(secondSetInterval);
                $(".timer_sec").text("00");
            }
            else
            {
             $(".option_list").removeClass("pointerNone");   
            }
        }
        else
        {
            diablingButtons();
        }
   });

   $(".next, .skip").click(function(){
        if (questionnumber<9) {
            $("#marquee").remove(); 
            clearInterval(secondSetInterval); 
            saveRadioBtnValue();
            questionnumber++;
            showquestionnum();
            showquestion();
            diablingButtons();
            indexpre=randomnumarr[questionnumber];
            index =quiz[indexpre]
            if (index.pointerEvents===true) {   
              $(".option_list").addClass("pointerNone");
              $(".quiz_box").prepend(`<marquee id="marquee" class="marquee my-2" width="100%" direction="right" height="20px">
                You cannot select any option Now.
                </marquee>`)
                clearInterval(secondSetInterval);
                $(".timer_sec").text("00");
            }
            else
            {
             $(".option_list").removeClass("pointerNone");   
            }
        }
        if (questionnumber==9) {
            $(".skip").addClass('disable');
            $(".next").hide();
            $(".result").show();
        }
   });

//    making a function for checking results -->
let CA=0;
let SA=0;
let WA=0;
function checkResults() {
    for (let i = 0; i < 9; i++) {
        let indexpre=randomnumarr[i];
        let index =quiz[indexpre]; 
        if (radioBtnChecked[i]==index.choices[index.correctAnswer]  ) {
            CA++;
        }  
        else if (radioBtnChecked[i]== " ") {
            SA++;
        }
        else
        {
             WA++;
        }      
    }
    // console.log(radioBtnChecked)
}

//    making a function for checking results <--

// CHECKING THE PERCENTAGE

let width1=0;
let perc1=0;
function gettingPerc() {
    perc1=(CA/10)*100;
    perc1=Math.round(perc1);
    width1=perc1;
    $(".perc-line").css({
        "width":`${width1}%`,
        "transition": "width 1s linear"
    });
}

let width2=0;
let perc2=0;
function gettingPercTime() {
    perc2=(totaltime/1800)*100;
    width2=perc2;
    $(".time-line").css({
        "width":`${width2}%`,
        "transition": "width 1s linear"
    });
}
// CHECKING THE PECENTAGE
            let lastCheckedVal;
    $(".result").click(function () {
        if(questionnumber==9);
        {
            let last = randomnumarr[9];
            let lastans = quiz[last];

            if($('.option').is(':checked'))
            {
                lastidVal =$('input[name="option"]:checked').attr("id");
                lastCheckedVal=$("label[for='"+lastidVal+"']").text();
            }else{
                lastCheckedVal== " "
            }

            if (lastCheckedVal==lastans.choices[lastans.correctAnswer]) {
                CA++;
            }  
            else if(lastCheckedVal== undefined) {

                SA++;
            }
            else
            {
               WA++;
           }  
            
       }
       
      
    $(".questionbody").remove();
    $(".resultbody").fadeIn();
    saveRadioBtnValue();
    clearInterval(totalSetInterval);
    checkResults();
    gettingPerc();
    gettingPercTime();
    $(".percentage").text(`${perc1}%`);
    $(".skip-ans-given").text(SA);
    $(".wrong-ans-given").text(WA);
    $(".Correct-ans-given").text(CA);
    $(".time").text(totaltime);
     if(CA > 5)
        {
        $(".span").show();

    }else{
        $(".span").hide();

    }

     // $.session.set("radioBtnChecked", radioBtnChecked);
     // $.session.set("lastCheckedVal", lastCheckedVal);
     // $.session.set("allquestion", allquestion);
  

    });
// adding the functionalities to buttons ends
    $(".icons i").click(function () {
        $(this).siblings().css(
            {
                "display" : "none"
            }, 1000);
            $(this).css({
                "color": "#FFB400"
            });
    });

   $(".pdf").click(function(){
    //   alert("hello");
    $(this).prop("disabled", true);
    // console.log(radioBtnChecked);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:'/generate/' +token,
        type:'POST',
        data:{'radioBtnChecked':radioBtnChecked,'lastCheckedVal':lastCheckedVal,
        'allquestion':allquestion,'correct':CA,'skip':SA,'wrong':WA,'user':user,'email':email},
        xhrFields: {
            responseType: 'blob'
        },
        success:function(response){
             
            var blob = new Blob([response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = user + ".pdf";
            link.click();
            $(".pdf").prop("disabled", false);
            window.location.href= "https://timeloggerapp.thinktanker.in/aptitude/thankyou";

        }
    });

   });

});
  </script>


  </body>
</html>