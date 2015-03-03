/**
 * Created by JHICKS on 2/27/2015.
 */

    // ============================================================================
    // ======================= Classified Ventures, 2000 ==========================
    // ============================================================================


    // Determine whether to do a zipcode search or city/state
    //     zipcode overrides city/state
    function deterLoad() {
        theCity = window.document.qs.city.value;
        theState = window.document.qs.state.options[window.document.qs.state.selectedIndex].value;
        theZip = window.document.qs.zip.value;
        theZip = encodeURIComponent(theZip);
        //Rent_Minimum = window.document.qs.Rent_Minimum.value;
        //Rent_Maximum = window.document.qs.Rent_Maximum.value;

        Rent_Minimum = '0';
        Rent_Maximum = '99999';

        if (theZip.length > 0) {
            return loadZS();
        }
        else if (theState != "") {
            return loadQS();
        }

        else {
            alert("Please enter a \"city and state\" or \"zipcode\".");
            return false;
        }
    }

function loadQS(w) {
    var theCity;
    var theState;
    var radius;
    var bool;

    //Rent_Minimum = window.document.qs.Rent_Minimum.value;
    //Rent_Maximum = window.document.qs.Rent_Maximum.value;

    Rent_Minimum = '0';
    Rent_Maximum = '99999';

    bedrooms = window.document.qs.bedrooms.options[window.document.qs.bedrooms.selectedIndex].value;
    //bedrooms = 'AllSizes';

    if (bedrooms == 'studio') {
        bedrooms1 = '&studio=0';
    }
    else if (bedrooms == 'onebdrm') {
        bedrooms1 = '&onebdrm=0';
    }
    else if (bedrooms == 'twobdrm') {
        bedrooms1 = '&twobdrm=0';
    }
    else if (bedrooms == 'threebdrm') {
        bedrooms1 = '&threebdrm=0';
    }
    else if (bedrooms == 'fourbdrm') {
        bedrooms1 = '&fourbdrm=0';
    }
    else if (bedrooms == 'fivebdrm') {
        bedrooms1 = '&fivebdrm=0';
    }
    else {
        bedrooms1 = ''
    }

    if (Rent_Minimum == "" || Rent_Maximum == "") {
        alert('Rent fields cannot be empty');
        return false;
    }
    else if (!IsNumeric(Rent_Minimum)) {
        alert('Rent fields should have numeric values only');
        document.qs.Rent_Minimum.focus();
        document.qs.Rent_Minimum.value = 0;

        return false;
    }
    else if (!IsNumeric(Rent_Maximum)) {
        alert('Rent fields should have numeric values only');
        document.qs.Rent_Maximum.focus();
        document.qs.Rent_Maximum.value = 99999;

        return false;
    }

    theCity = window.document.qs.city.value;
    theState = window.document.qs.state.options[window.document.qs.state.selectedIndex].value;
    radius = window.document.qs.rad.options[window.document.qs.rad.selectedIndex].value;

    if (radius == '5' || radius == '10' || radius == '20') {
        if (theCity == "") {
            alert("Please enter a City");
            document.qs.city.focus();
            return false;
        }
    }

    bool = validateForm(theState);
    if (bool == "n") {
        return false;
    }
    else {
        theCity = encodeURIComponent(theCity);

        var qString;
        if (document.qs.rad.value == '5' || document.qs.rad.value == '10' || document.qs.rad.value == '20') {
            qString = "http://www.apartments.com/partner/Rent.aspx?page=rent&stype=city&city=" + theCity + "&state=" + theState + "&rad=" + radius + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
        }
        else {
            qString = "http://www.apartments.com/partner/Results.aspx?page=results&stype=city&city=" + theCity + "&state=" + theState + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
        }
        window.document.location = qString;

        return false;
    }
}

function validateForm(st) {
    var daState = st;

    if (daState == "") {
        alert("Please Select a State from the menu")
        return ("n");
    }
    else {
        return ("y");
    }
}
//NxB

function loadZS() {
    var theZip;
    var bool;
    //Rent_Minimum = window.document.qs.Rent_Minimum.value;
    //Rent_Maximum = window.document.qs.Rent_Maximum.value;
    Rent_Minimum = '0';
    Rent_Maximum = '99999';
    bedrooms = window.document.qs.bedrooms.options[window.document.qs.bedrooms.selectedIndex].value;
    //bedrooms = 'AllSizes';
    if (bedrooms == 'studio') {
        bedrooms1 = '&studio=0';
    }
    else if (bedrooms == 'onebdrm') {
        bedrooms1 = '&onebdrm=0';
    }
    else if (bedrooms == 'twobdrm') {
        bedrooms1 = '&twobdrm=0';
    }
    else if (bedrooms == 'threebdrm') {
        bedrooms1 = '&threebdrm=0';
    }
    else {
        bedrooms1 = ''
    }

    if (Rent_Minimum == "" || Rent_Maximum == "") {
        alert('Rent fields cannot be empty');
        return false;
    }
    else if (!IsNumeric(Rent_Minimum)) {
        alert('Rent fields should have numeric values only');
        document.qs.Rent_Minimum.focus();
        document.qs.Rent_Minimum.value = 0;

        return false;
    }
    else if (!IsNumeric(Rent_Maximum)) {
        alert('Rent fields should have numeric values only');
        document.qs.Rent_Maximum.focus();
        document.qs.Rent_Maximum.value = 99999;

        return false;
    }

    theZip = document.qs.zip.value;
    theZip = encodeURIComponent(theZip);

    if (theZip.length < 5) {
        alert("Please enter one/multiple 5 digit zip code/s seperated by commas");
        return false;
    }

    var qString;
    if (document.qs.rad.value == '5' || document.qs.rad.value == '10' || document.qs.rad.value == '20') {
        qString = "http://www.apartments.com/partner/Results.aspx?page=results&stype=zip&zip=" + theZip + "&rad=" + document.qs.rad.value + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
    }
    else {
        qString = "http://www.apartments.com/partner/Results.aspx?page=results&stype=zip&zip=" + theZip + "&partner=green%20bay" + "&Rent_Minimum=" + Rent_Minimum + "&Rent_Maximum=" + Rent_Maximum + bedrooms1;
    }
    window.document.location = qString;
    return false;
}

function ValidateZip(WebSearchFrm) {
    if (WebSearchFrm.txtZipCode.value == "") {
        alert('Missing ZIP information. Please enter a ZIP code to post a listing')
        WebSearchFrm.txtZipCode.focus();
        return false;
    }

    if (!IsNumeric(WebSearchFrm.txtZipCode.value)) {
        alert('Please enter only numbers in the Zip Code')
        WebSearchFrm.txtZipCode.focus();
        return false;
    }
    return true;
}

function Form_Validation(WebSearchFrm) {
    if (WebSearchFrm.txtContactEmail.value == "") {
        alert("Please enter a value for the Email field.");
        WebSearchFrm.txtContactEmail.focus();
        return (false);
    }

    var checkOK = "_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz&#402;&#352;&#338;&#353;&#339;&#376;Ãƒâ‚¬ÃƒÂÃƒâ€šÃƒÆ’ÃƒÃƒâ€¦Ãƒâ€ Ãƒâ€¡ÃƒË†Ãƒâ€°ÃƒÅ Ãƒâ€¹ÃƒÅ’ÃƒÂÃƒÅ½ÃƒÂÃƒÂÃƒ'Ãƒ'ÃƒÃƒÃƒâ€¢Ãƒâ€“ÃƒËœÃƒâ„¢ÃƒÅ¡Ãƒâ€ºÃƒÅ“ÃƒÂÃƒÅ¾ÃƒÅ¸Ãƒ ÃƒÂ¡ÃƒÂ¢ÃƒÂ£ÃƒÂ¤ÃƒÂ¥ÃƒÂ¦ÃƒÂ§ÃƒÂ¨ÃƒÂ©ÃƒÂªÃƒÂ«ÃƒÂ¬ÃƒÂ­ÃƒÂ®ÃƒÂ¯ÃƒÂ°ÃƒÂ±ÃƒÂ²ÃƒÂ³ÃƒÂ´ÃƒÂµÃƒÂ¶ÃƒÂ¸ÃƒÂ¹ÃƒÂºÃƒÂ»ÃƒÂ¼ÃƒÂ½ÃƒÂ¾0123456789-@. \t\r\n\f";
    var checkStr = WebSearchFrm.txtContactEmail.value;
    var allValid = true;
    for (i = 0; i < checkStr.length; i++) {
        ch = checkStr.charAt(i);
        for (j = 0; j < checkOK.length; j++)
            if (ch == checkOK.charAt(j))
                break;
        if (j == checkOK.length) {
            allValid = false;
            break;
        }
    }
    if (!allValid) {
        alert("Please enter only letter, digit, whitespace and \"@.\" characters in the Contact Email field.");
        WebSearchFrm.txtContactEmail.focus();
        return (false);
    }

    if (!(IsEmailValid(WebSearchFrm.txtContactEmail)))
        return (false);

    return true;
}

function IsNumeric(sText) {
    var ValidChars = "0123456789";
    var IsNumber = true;
    var Char;

    for (i = 0; i < sText.length && IsNumber == true; i++) {
        Char = sText.charAt(i);
        if (ValidChars.indexOf(Char) == -1) {
            IsNumber = false;
        }
    }
    return IsNumber;
}

function IsEmailValid(ElemName) {
    var EmailOk = true;
    var Temp = ElemName;
    var AtSym = Temp.value.indexOf('@');
    var Period = Temp.value.lastIndexOf('.');
    var Space = Temp.value.indexOf(' ');
    var Length = Temp.value.length - 1;   // Array is from 0 to length-1
    if ((AtSym < 1) ||                     // '@' cannot be in first position
        (Period <= AtSym + 1) ||             // Must be atleast one valid char btwn '@' and '.'
        (Period == Length ) ||             // Must be atleast one valid char after '.'
        (Space != -1))                    // No empty spaces permitted
    {
        EmailOk = false;
        alert('Please enter a valid e-mail address!');
        Temp.focus();
    }
    return EmailOk;
}

//~NxB
//-->

