$('body').bind('contextmenu', function(e) {
        return false;
});

let repForm = document.forms['reportForm'];  //Report Form
let myModal = document.querySelector("#myModal");

let modalCloseBtn = document.querySelector("#btnCloseModal");

modalCloseBtn.addEventListener('click',closeModal); //CLose Modal

//repForm.addEventListener('submit',operate);
repForm.addEventListener('submit',upload);

//Preview Image Evidence
repForm['evidence'].addEventListener('change',function(){
  let evidence = repForm['evidence'];
  file =  evidence.files[0];
  repForm['preview'].src=URL.createObjectURL(file);
});
////////
function closeModal()
{
    myModal.style.display='none';
    myModal.classList.toggle("fade");
    document.body.classList.toggle('modal-open');
    window.location=".";
    /*document.querySelector('.modal-title').innerHTML = "Notification";
    document.querySelector('.modal-body').innerHTML = data;*/
}

function operate(e){
    e.preventDefault();
    let  title = repForm['title'];
    let desc = repForm['desc'];
    let evidence = repForm['evidence'];
    titleTxt = title.value;
    descTxt = desc.value;
    //file =  evidence.files[0];


    $.post('addfile.php',{title: titleTxt, desc: descTxt,},
    function(data){

       //upload(e);
    });
}

function upload(e){
    e.preventDefault();
    let  title = repForm['title'];
    let desc = repForm['desc'];
    titleTxt = title.value;
    descTxt = desc.value;

    let evidence = repForm['evidence'];
    file =  evidence.files[0];
    var formData = new FormData();
    formData.append("evidence", file);

    $.ajax({
        url: 'upload.php', // <-- point to server-side PHP script
        dataType: 'text',  // <-- what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        type: 'post',
        //before: operate(e),
        success: function(data){
            if(data!="0")
            {   // if Evidence was successfully uploaded
                let  title = repForm['title'];
                let desc = repForm['desc'];
                let evidence = repForm['evidence'];
                titleTxt = title.value;
                descTxt = desc.value;
                //Insert into db
                $.post('addfile.php',{data: data, title: titleTxt, desc: descTxt,},
                function(dt){
                    myModal.style.display='block';
                    myModal.classList.toggle("fade");
                    document.body.classList.toggle('modal-open');
                    document.querySelector('.modal-title').innerHTML = "Notification";
                    document.querySelector('.modal-body').innerHTML = "Report Submitted Successfully";
                    //document.querySelector('.modal-body').innerHTML = dt;
                });
            }
            else{
                myModal.style.display='block';
                    myModal.classList.toggle("fade");
                    document.body.classList.toggle('modal-open');
                    document.querySelector('.modal-title').innerHTML = "Notification";
                    document.querySelector('.modal-body').innerHTML = "<p>File Format is Bad</p> Only .png,.jpg,.mp3,.mp4,.3gp files less than 1.2mb allowed";
            }

            // <-- display response from the PHP script, if any
        }
     });
}