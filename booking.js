//Jason Lu 17985133
//Deal with the request from booking.html
// using POST method 
var xhr = createRequest(); 
function getData(dataSource, divID, cname,phone, unumber, snumber, stname, sbname, dsbname, date, time) {
	//Chcek if empty name input
	if(cname==''||cname==undefined||cname==null){
		alert("Customer name can not be empty");
	}
	//Chcek if empty phone input
	else if(phone==''||phone==undefined||phone==null){
		alert("Phone number can not be empty");
	}
	//Chcek if empty phone input
	else if(snumber==''||snumber==undefined||snumber==null){
		alert("Street number can not be empty");
	}
	//Chcek if empty street name input
	else if(stname==''||stname==undefined||stname==null){
		alert("Street name can not be empty");
	}
	//Chcek if empty date input
	else if(date==''||date==undefined||date==null){
		alert("date can not be empty");
	}
	//Chcek if empty time input
	else if(time==''||time==undefined||time==null){
		alert("time can not be empty");
	}else{
		//Chcek if phone number is vaild (10 to 12 number)
		if(checkPhoneNum(phone)){
			//check if data is earlier than current time and date
			if(checkDate()){
				if(xhr) { 
					var obj = document.getElementById(divID); 
					var requestbody = 
					"cname="+encodeURIComponent(cname)+
					"&phone="+encodeURIComponent(phone)+
					"&unumber="+encodeURIComponent(unumber)+
					"&snumber="+encodeURIComponent(snumber)+
					"&stname="+encodeURIComponent(stname)+
					"&sbname="+encodeURIComponent(sbname)+
					"&dsbname="+encodeURIComponent(dsbname)+
					"&date="+encodeURIComponent(date)+
					"&time="+encodeURIComponent(time);
					xhr.open("POST", dataSource, true); 
					xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
					xhr.onreadystatechange = function() { 
						if (xhr.readyState == 4 && xhr.status == 200) { 
							obj.innerHTML = xhr.responseText;
						} // end if 
					} // end anonymous call-back function 
					//console.log(requestbody); 
					xhr.send(requestbody); 
					formReset();
				} // end if 
			}else{
				alert("Pick-up date and time must not be earlier than current date and time");
			}
		}else{
			alert("Phone number should be all number and contains 10 to 12 numbers");
		}
	}
} // end function getData()


// file xhr.js
function createRequest() {
    var xhr = false;  
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return xhr;
} // end function createRequest()

//check if data is earlier than current time and date
function checkDate(){
	var date = new Date()
	var pickupDate= document.getElementById('date').value;
	var pickupTime= document.getElementById('time').value;
	var d = pickupDate.split("-");
    var t = pickupTime.split(":");
    var pickup = new Date(d[0],d[1]-1,d[2],t[0],t[1],'00');

	if(date < pickup){
		return true;
    }else{
		return false;
	}
}

//Reset after submit form
function formReset(){
	document.getElementById("requestForCab").reset();
}

//Chcek if phone number is vaild (10 to 12 number)
function checkPhoneNum(PNUM){
	var Number = PNUM;
	var patt=/^[0-9]{10,12}$/;
	var result=patt.test(Number);
	return result;
}