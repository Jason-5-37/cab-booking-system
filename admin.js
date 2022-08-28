//Jason Lu 17985133
//Deal with requst from admin.html
// using POST method 
var xhr = createRequest(); 
function SearchRequest(dataSource, divID, SearchInput) {
    //check if the search input is vaild
    if(checkSearch(SearchInput)){
        if(xhr) {
            var obj = document.getElementById(divID);
            var requestbody = "SearchInput="+encodeURIComponent(SearchInput)
            xhr.open("POST", dataSource, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    obj.innerHTML = xhr.responseText;
                } // end if 
            } // end anonymous call-back function
            //console.log(requestbody);
            xhr.send(requestbody);
        } // end if
    }else{
        alert("Wrong format! Please start with BRN and end wiht 5 numbers. Example: BRN00000 . And no any space can be in the start or end");
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

//Send Booking number by Assign button
function AssignCab(AssignBRN){
    if(xhr) {
        var obj = document.getElementById('content');
        var requestbody = 
        "AssignBRN="+encodeURIComponent(AssignBRN)+
        "&SearchInput="+encodeURIComponent("NotNull")
        xhr.open("POST", 'admin.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                obj.innerHTML = xhr.responseText;
            } // end if 
        } // end anonymous call-back function
        //console.log(requestbody);
        xhr.send(requestbody);
    } // end if
}

//Check if Search input is valid
function checkSearch(SInput){
    if(SInput==''||SInput==undefined||SInput==null){
        return true
    }else{
        var Input = SInput;
        var patt=/^BRN[0-9]{5,5}$/;
        var result=patt.test(Input);
        return result;
    }
}