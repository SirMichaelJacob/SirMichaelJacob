//let div1 = document.querySelector("#step1");
let loginForm = document.forms['loginForm'];
let regForm = document.forms['regForm'];

let panel1= document.querySelector('#panel1');
//let loginPanel = document.querySelector('#loginPanel');


let myModal = document.querySelector("#myModal");

let modalCloseBtn = document.querySelector("#btnCloseModal");
let picModal = document.querySelector("#picModal");

let picCloseBtn = document.querySelector("#btnClosePicModal");

picCloseBtn.addEventListener('click',closePicModal);



////////////////////
var trs = document.querySelectorAll("tr.reportItem");
for (var i = 0; i < trs.length; i++)
  (function (e) {
    trs[e].addEventListener("click", function () {
       //alert('title: '+this.children[0].textContent +' Desc: '+ this.children[1].textContent +' Reporter: '+ this.children[2].textContent);
      var t=this.children[0].textContent.trim() ;
      var desc = this.children[1].textContent.trim() ;
      var rep = this.children[2].textContent.trim();

      myModal.style.display='block';
      myModal.classList.toggle("fade");
      document.body.classList.toggle('modal-open');
      document.querySelector('.modal-title').innerHTML = "Vet Report";
      document.querySelector('#btnOther').style.display='none';

      div1 = document.createElement('div');
      document.querySelector('.modal-body').appendChild(div1);

      lblTitle = document.createElement('Label');
      lblTitle.innerHTML = "<i>About:</i> <div style='font-weight: lighter'>"+ this.children[0].textContent + "</div>" ;
      //document.querySelector('.modal-body').appendChild(lblTitle);
      div1.appendChild(lblTitle);

      let p =  document.createElement('p');

      div1.appendChild(p);

      //document.querySelector('.modal-body').appendChild(p);

      lblDesc = document.createElement('Label');
      lblDesc.innerHTML = "<i>Allegation</i> <div style='Color:red; font-weight: lighter'>"+ this.children[5].value + "</div>";
      //document.querySelector('.modal-body').appendChild(lblDesc);

      div1.appendChild(lblDesc);

      p =  document.createElement('p');

      div1.appendChild(p);
      //ocument.querySelector('.modal-body').appendChild(p);

      lblReporter = document.createElement('Label');
      //lblReporter.style.color = 'green';
      lblReporter.innerHTML = "<i>Reporter</i> <div style='Color:green'>"+ this.children[2].textContent.trim() +"</div>";

      div1.appendChild(lblReporter);

      p =  document.createElement('p');
      div1.appendChild(p);

      link = document.createElement('a');
      link.href = "?rcase="+t+"&rep="+rep.toUpperCase();
      link.id = "link";
      link.innerHTML = "";

      div1.appendChild(link);

      btnCopy = document.createElement('button');
      btnCopy.className="btn btn-info btn-xs";
      btnCopy.id= "btnCopy";
      btnCopy.innerHTML='Click to Copy Link and Share on social media';
      div1.appendChild(btnCopy);

      div1.appendChild(btnCopy);

      btnCopy.addEventListener('click',()=>copyText(link,btnCopy));

      p =  document.createElement('p');
      div1.appendChild(p);
      image = document.createElement('img');
      image.style.width = '80px';
      image.style.width = '100px';
      image.src ='confidential/'+this.children[6].value + ".jpg" ;
      div1.appendChild(image);
      image.addEventListener('click',function(){
         //Close Modal
        myModal.style.display='none';
        myModal.classList.toggle("fade");
        myModal.classList.toggle('modal-open');
         //
         picModal.style.display='block';
         picModal.classList.toggle("fade");
         picModal.classList.toggle('modal-open');
         btnPay =  document.createElement('button');
         btnPay.className="btn btn-primary btn-xs";
         btnPay.id= "btnPay";
         btnPay.innerHTML='Click to Pay and Download';
         document.querySelector('#picModal .modal-title').innerHTML = t + " ";
         document.querySelector('#picModal .modal-title').appendChild(btnPay);
         newImage= this;
         newImage.style.width = '280px';
         newImage.style.width = '300px';
         newImage.classList.toggle('blur');
         document.querySelector('#picModal .modal-body').appendChild(newImage);
      });

      p =  document.createElement('p');
      div1.appendChild(p);

      div = document.createElement('div');
      div.className = "col-md-10 has-success" ;
      div.style.display ='flex';
      div.style.justifyContent= 'center';
      document.querySelector('.modal-body').appendChild(div);

      form1 = document.createElement('form');
      form1.method ='post';

      vetEmail = document.createElement('input');
      vetEmail.type ='email';
      vetEmail.name ="vetEmail";
      vetEmail.className ="form-control";
      vetEmail.id = 'success';
      vetEmail.placeholder = "Enter your Email";
      document.querySelector('.modal-body').appendChild(vetEmail);

      p =  document.createElement('p');

      form1.appendChild(p);

      btnVet = document.createElement('button');
      btnVet.type ='submit';
      btnVet.name='vet';
      btnVet.className = "btn btn-primary btn-lg";
      //btnVet.classList.add("btn","btn-default","btn-sm");
      btnVet.textContent = 'Confirm';

      form1.appendChild(btnVet);

      span =  document.createElement('span');
      span.style.margin ='20px';
      form1.appendChild(span);



      div.appendChild(form1);

      document.querySelector('.modal-body').appendChild(div);

      btnVet.addEventListener('click',function (e){
          e.preventDefault();
          //alert(rep);
          vet(t,rep,vetEmail.value);
          });

      //document.querySelector('.modal-body').innerHTML = "Vet Report?";
    }, false);
  })(i);
///////////////////////////////

function vet(title,username,email){
    email =  email.trim().toUpperCase();
    username =  username.trim().toUpperCase();

    $.post("vet.php", { title: title , username:username, vetEmail:email},
   function(data)
   {
      if(data =="successful")
     {
        myModal.style.display='block';
        myModal.classList.remove("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('#myModal .modal-title').innerHTML = "Vet Report";
        document.querySelector('#myModal .modal-body').innerHTML= "Vetting Successful!";
     }
     else{
        myModal.style.display='block';
        myModal.classList.remove("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('#myModal .modal-title').innerHTML = "Vet Report";
        document.querySelector('#myModal .modal-body').innerHTML= data;
     }

   });
}


modalCloseBtn.addEventListener('click',closeModal); //CLose Modal

//repForm.addEventListener('submit',operate,false);

/*reportItems.addEventListener('click',function(){
    alert("Clicked");
});*/

function closeModal()
{
      myModal.style.display='none';
      myModal.classList.toggle("fade");
      document.body.classList.toggle('modal-open');
      window.location=".";
      /*document.querySelector('.modal-title').innerHTML = "Notification";
      document.querySelector('.modal-body').innerHTML = data;*/
}

function closePicModal()
{
      picModal.style.display='none';
      picModal.classList.toggle("fade");
      document.body.classList.toggle('modal-open');
      window.location=".";
}


function sendData(e){

    e.preventDefault();

    let usernameTxt = username.value;
    let emailTxt = email.value;

    $.post("login.php", { username: usernameTxt.trim() , email:emailTxt.trim()},
   function(data)
   {

     if(data === "verified")
     {
        document.cookie = "signedIn=True";
        //loginPanel.style.display='none';
        window.location.href='.';
     }
     else if(data === 'unverified')
     {
        myModal.style.display='block';
        myModal.classList.toggle("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('.modal-title').innerHTML = "Notification";
        document.querySelector('.modal-body').innerHTML = "Your Email has not been verified.";
        //<button type="button" class="btn btn-default" id='btnVerify'><a href='?verify'>Verify</a></button>
        /*btnVerify = document.createElement('button');
        btnVerify.className='btn btn-default';
        btnVerify.id='btnVerify';*/
        p = document.createElement('p');

        aLink = document.createElement('a');
        aLink.innerHTML = 'Verify Your Email';
        aLink.href='?verify';

        p.appendChild(aLink);
        /*btnVerify.appendChild(aLink);*/

        document.querySelector('.modal-body').appendChild(p);

        document.querySelector('#btnOther').style.display = "none";

     }
     else if(data === "User not found")
     {
        myModal.style.display='block';
        myModal.classList.toggle("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('.modal-title').innerHTML = "Notification";
        document.querySelector('.modal-body').innerHTML = data;

     }
   });
}

function register(e){

    e.preventDefault();

    let usernameTxt = username_reg.value;
    let emailTxt = email_reg.value;

    $.post("reg.php", { username: usernameTxt , email:emailTxt},
   function(data)
   {

     if(data =="1")
     {
        window.location.href='.';
     }
     else
     {
        myModal.style.display='block';
        myModal.classList.toggle("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('.modal-title').innerHTML = "Notification";
        document.querySelector('.modal-body').innerHTML = data;

     }
   });
}


function cutText(text, length) {
    if (text == null) {
        return "";
    }
    if (text.length <= length) {
        return text;
    }
    text = text.substring(0, length);
    last = text.lastIndexOf(" ");
    text = text.substring(0, last);
    return text + "...";
}

function copyText(target,element)
{
    var str="";

    var aux = document.createElement("div");
      aux.setAttribute("contentEditable", true);
      aux.innerHTML = target.href;
      str = aux.innerHTML;
      aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)");
      document.body.appendChild(aux);
      aux.focus();
      document.execCommand("copy");
      document.body.removeChild(aux);

    if(str.length>0){
      element.innerHTML="Link Copied";
      element.className="btn btn-success btn-xs";
    }
    else{
      element.innerHTML="Not Copied";
    }

}

//console.log(document.cookie);
