<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
    <style>
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

    </style>
</head>

  <body>
    <div class="container-fluid px-0">
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
                              ">LINK EXPIRED</div>
                  <div class="time_line"></div>
                </header>
                <section class="result-showing-box">
                    <div class="title" style="
                                  margin-top: 34px;
                                  font-size: 30px;
                                  font-weight: 700;
                                  color: black;
                                  text-align: center;
    							  margin-bottom: 34px;
                              ">
                              This URL is not Valid Anymore.
                            </div>
                
                </section>
                      <div class="time-line" style="height: 3px;"></div>
                <footer style="height: 70px;" class="d-flex flex-column" >
                </footer>
              </div>
            </div>
          </div>
        </div>
    </div>



  </body>
</html>
