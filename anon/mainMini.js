let btnVet = document.querySelector('#btnVet');
var t=   document.querySelector('#t').innerHTML;
var rep = document.querySelector('#rep').innerHTML;
var link = document.querySelector('#link');
var reportImg = document.querySelector('#rImg');


reportImg.addEventListener('click',function(){
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

         /*image = document.createElement('img');
         image.style.width = '80px';
         image.style.width = '100px';
         image.src ='confidential/'+this.children[6].value + ".jpg" ;*/
         newImage= this;
         newImage.style.width = '280px';
         newImage.style.width = '300px';
         newImage.classList.toggle('blur');
         document.querySelector('#picModal .modal-body').appendChild(newImage);
      });

var btnCopy = document.querySelector('#btnCopy');
btnCopy.className="btn btn-info btn-xs";

btnVet.addEventListener('click',function (e){
          e.preventDefault();
          //alert(rep);
          vet(t,rep);
          },false);

btnCopy.addEventListener('click',()=>copyText(link,btnCopy));


function vet(title,username){
    title = title.trim();
    username =  username.trim().toUpperCase();

    $.post("vet.php", { title: title , username:username},
   function(data)
   {
     //alert(username);
     //alert(data);
     if(data==="successful")
     {
        myModal.style.display='block';
        myModal.classList.remove("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('.modal-title').innerHTML = "Vet Report";
        document.querySelector('.modal-body').innerHTML= "Vetting Successful!";
     }
     else{
        myModal.style.display='block';
        myModal.classList.remove("fade");
        document.body.classList.toggle('modal-open');
        document.querySelector('.modal-title').innerHTML = "Vet Report";
        document.querySelector('.modal-body').innerHTML= data;
     }

   });
}

document.querySelectorAll('.close')[0].addEventListener('click',closeModal);

function closeModal()
{
      myModal.style.display='none';
      myModal.classList.toggle("fade");
      document.body.classList.toggle('modal-open');
      window.location=".";
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

