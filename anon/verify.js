let verifyForm = document.forms['verifyForm'];
let myModay = document.querySelector("#myModal");

let modalCloseBtn = document.querySelector("#btnCloseModal");

let  code = verifyForm['code'];

let next="";

verifyForm.addEventListener('submit',verifyEmail,false); //Submit Verification code

modalCloseBtn.addEventListener('click',closeModal,false); //CLose Modal

function verifyEmail(e)
{
    e.preventDefault();
    let codeTxt = code.value;
    $.post("reg.php", { code: codeTxt},
   function(data)
   {
     //alert(data);
     if(data=='Email Verified')
     {

        myModal.style.display='block';
        myModal.classList.toggle("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('.modal-title').innerHTML = "Notification";
        document.querySelector('.modal-body').innerHTML = data;
        document.querySelector('#btnOther').style.display = "none";
        next=".";
     }
     else
     {
        myModal.style.display='block';
        myModal.classList.toggle("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('.modal-title').innerHTML = "Notification";
        document.querySelector('.modal-body').innerHTML = data;
        document.querySelector('#btnOther').style.display = "none";
        next="?verify";
     }
   });
}

function closeModal()
{
    window.location.href=location;
      myModal.style.display='none';
      myModal.classList.toggle("fade");
      document.bodyclassList.toggle('modal-open');

      /*document.querySelector('.modal-title').innerHTML = "Notification";
      document.querySelector('.modal-body').innerHTML = data;*/
}