

const dialog = document.getElementById("favDialog");
const eventDialog = document.getElementById(('eventDialog'))
const card1 = document.getElementById("card1");
const weekNo = document.getElementById('weekNo')
const totalSumma = document.getElementById('totalSumma')
const currentOrgId  = document.getElementById('currentOrgId')
var fetchEvents = true // Set to true whenever new fetch is needed
const utbildarMap = new Map()

const kurser = new Map()

const kurserAttValja = document.getElementById('kurserAttValja')
const selectedCourseName = document.getElementById('selectedCourseName')
const selectedCourseStart = document.getElementById('selectedCourseStart')
const selectedCourseEnd = document.getElementById('selectedCourseEnd')
const selectedCourseHoursLeft =   document.getElementById('selectedCourseHoursLeft')
const selectedCourseHoursTotal =   document.getElementById('selectedCourseHoursTotal')
const dialogKursName=   document.getElementById('dialogKursName')
const dialogKlassName=   document.getElementById('dialogKlassName')
const dialogNotes =   document.getElementById('dialogNotes')
const dialogTeacher = document.getElementById('dialogTeacher')
const dialogLocation = document.getElementById('dialogLocation')
const dialogLunch = document.getElementById('dialogLunch')
const btnSave = document.getElementById('btnSave')
const btnCloseDialog = document.getElementById('btnCloseDialog')
const btnDelete = document.getElementById('btnDelete')

let serverOptions = null

const toggleAll = document.getElementById('toggleAll')
let selectedCourse  = null
let selectedCourseKey = ""

let editingInfo = null

toggleAll.addEventListener('click', ()=>{
    if(toggleAll.innerText === "visa alla"){
        toggleAll.innerText = "visa kvar"
    }else{
        toggleAll.innerText = "visa alla";
    }
    refreshKurserAttValja()
    return false

});

selectedCourseStart.addEventListener("click", ()=>{
    ec.setOption('date', selectedCourse.startDate);
})
selectedCourseEnd.addEventListener("click", ()=> {
    ec.setOption('date', selectedCourse.endDate);
})


dialog.addEventListener("click", (event)=>{
    if (event.target === dialog) {
        dialog.close();
    }
});
eventDialog.addEventListener("click", (event)=>{
    if (event.target === eventDialog) {
        eventDialog.close();
    }
});


function getTeacher(id){
    var ret = null
    serverOptions.teachers.forEach(c=>{
        if(c.id == id)
            ret = c;
    })
    return ret
}

function getLocation(id){
    var ret = null
    serverOptions.locations.forEach(c=>{
        if(c.id == id)
            ret = c;
    })
    return ret
}


btnDelete.addEventListener('click', ()=>{
    if (!confirm('Är du säker att ta bort denna?')) return;
    if(editingInfo.event && editingInfo.event.id > 0) {
        deleteEvent(editingInfo.event.id)
    }
    eventDialog.close();
    return false

});




btnSave.addEventListener('click',()=>{
    if(editingInfo.event && editingInfo.event.id > 0){
        editingInfo.event.extendedProps.lunch = dialogLunch.value;
        editingInfo.event.extendedProps.teacher = getTeacher(dialogTeacher.value);
        editingInfo.event.extendedProps.location = getLocation(dialogLocation.value);
        editingInfo.event.extendedProps.notes = dialogNotes.value;


        saveEvent(editingInfo.event.id,editingInfo.event.start.toISOString(),editingInfo.event.end.toISOString(), dialogTeacher.value,
            editingInfo.event.resourceIds[0],
            //,
            editingInfo.event.extendedProps.kurs.id,
            editingInfo.event.extendedProps.klass.id,
            dialogLocation.value,dialogNotes.value, dialogLunch.value  )

    }
    else{
        saveEvent(0,editingInfo.start.toISOString(),editingInfo.end.toISOString(), dialogTeacher.value,
            editingInfo.resource.id,
            //,
            selectedCourse.id, selectedCourse.klassid,
            dialogLocation.value,dialogNotes.value, dialogLunch.value  )

    }
    eventDialog.close();
    return false
})

btnCloseDialog.addEventListener('click',()=>{
    eventDialog.close();
    return false
})






let ec = new EventCalendar(document.getElementById('ec'), {
    datesSet: function (info){
        vecka = ISO8601_week_no(info.start)
        weekNo.innerText = 'Vecka ' + vecka
    },
    view: 'resourceTimeGridWeek',
    height: '800px',
    select:function(info){
        editingInfo = info
        newEventDialog()
    },
    eventClick:function (info){
        editingInfo = info
        editEventDialog()
    },
    eventResize:function(info){
        saveEvent(info.event.id, info.event.start.toISOString(),
            info.event.end.toISOString(),
            info.event.extendedProps.teacher.id,
            info.newResource ? info.newResource.id : info.event.resourceIds[0],
            //     //info.event._def.resourceIds[0],
            info.event.extendedProps.kurs.id,
            info.event.extendedProps.klass.id,
            info.event.extendedProps.location.id, info.event.extendedProps.notes, info.event.extendedProps.lunch);
    },

    eventDrop:function(info){
        //console.dir(info)
        saveEvent(info.event.id, info.event.start.toISOString(),
             info.event.end.toISOString(),
             info.event.extendedProps.teacher.id,
             info.newResource ? info.newResource.id : info.event.resourceIds[0],
        //     //info.event._def.resourceIds[0],
             info.event.extendedProps.kurs.id,
             info.event.extendedProps.klass.id,
             info.event.extendedProps.location.id, info.event.extendedProps.notes,info.event.extendedProps.lunch);
    },
    headerToolbar: {
        start: 'prev,next today',
        center: 'title',
        end: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek resourceTimeGridWeek'
    },
    buttonText: function (texts) {
        texts.resourceTimeGridWeek = 'resources';
        return texts;
    },
    resources: [
        {id: 1, title: 'Resource A'},
        {id: 2, title: 'Resource B'}
    ],
    scrollTime: '07:00:00',
    slotMinTime:'07:00:00',
    slotMaxTime:'20:00:00',
    plugins: ['resourceTimeGrid', 'interaction'],
    firstDay:1,
    locale:'sv',
    hiddenDays:[0,6],
    datesAboveResources:true,
    lazyFetching:false,
    loading:function(isloading){
        if(!isloading)  {
            fetched = true;
            recalculate()

        }
    },
    eventSources:[
        {events: loadEvents},
        {events: loadHolidays}
    ],

    // eventSources:[ {
    //     url: '/scheduleadmin/events',
    //     method: 'GET',
    //     extraParams: function () { // a function that returns an object
    //         return {
    //             dynamic_value: Math.random()
    //         }
    //     },
    //     failure: function () {
    //         alert('there was an error while fetching events!');
    //     },
    // }],
    views: {
        timeGridWeek: {pointer: true},
        resourceTimeGridWeek: {pointer: true}
    },
    dayMaxEvents: true,
    nowIndicator: true,
    selectable: true,
    editable:true
});


refreshOptions(ec)


function deleteEvent(id) {
    var url = '/scheduleadmin/' + currentOrgId.value +  '/deleteevent?id=' + id;
    $.get(url).done(function (data) {
        fetchEvents = true;
        ec.refetchEvents();
    });

}


function saveEvent(id, start, end, teacherid, roomid, kursid, klassid, locationId,notes, lunch) {
    var url = '/scheduleadmin/' + currentOrgId.value +  '/saveevent?start=' + start + '&end=' + end + '&locationId=' + locationId + '&teacherid=' + teacherid + '&roomid=' + roomid + '&kursid=' + kursid + '&klassid=' + klassid + '&id=' + id + "&notes=" + notes + "&lunch=" + lunch;
    console.log(url)
    $.get(url).done(function (data) {
        if(id == 0){
            fetchEvents = true;
            ec.refetchEvents();
        }
        else{
            recalculate()
            refreshKursData(selectedCourseKey)
        }
    });
}

function loadHolidays(fetchInfo, successCallback, failureCallback) {
    // if(!fetchEvents) {
    //     recalculate()
    //     return
    // }
    console.dir(fetchInfo)
    fetch("/public/api/holidays?cb=" + Math.random() + "&end=" +fetchInfo.end.toISOString() + "&start=" +  fetchInfo.start.toISOString())
        .then(response => response.json())
        .then(json => {
            //successCallback(json);
        });
}



function loadEvents(fetchInfo, successCallback, failureCallback) {
    if(!fetchEvents) {
        recalculate()
        refreshKursData(selectedCourseKey)
        return
    }
    fetch("/scheduleadmin/" + currentOrgId.value +  "/events?cb=" + Math.random())
        .then(response => response.json())
        .then(json => {
            fetchEvents = false
            successCallback(json);
            recalculate()
            refreshKursData(selectedCourseKey)
            //ec.setOption('resources',serverOptions.resources)
        });
}



function refreshOptions(ec){

    fetch("/scheduleadmin/" + currentOrgId.value +  "/options?cb=" + Math.random())
        .then(response=>response.json())
        .then(json=>{
            serverOptions =  { resources: [], teachers:[] }
            serverOptions.resources = json.rooms.map(m=>{ return {id:m.id, title:m.name }})
            serverOptions.teachers = json.teachers
            serverOptions.locations = json.locations
            setKurser(json.kurser)
            ec.setOption('resources',serverOptions.resources)
            recalculate()
        });
}


function setKurser(kursArray) {
    kursArray.forEach(c=>{
        var title = c.title
        if(!kurser.has(title)){
            kurser.set(title,{ "id": c.id,
                "totalHours": c.totalHours,
                "title": title,
                "defaultTeacher": {
                    "id": c.defaultTeacher.id,
                    "name": c.defaultTeacher.name
                },
                "defaultLocation": c.defaultLocation,
                "startDate": c.startDate,
                "endDate": c.endDate,
                "name": c.name,
                "klassname": c.klassname,
                "klassid": c.klassid,
                "backgroundColor": c.klasscolor,
                "scheduledCurrent": 0,
                "scheduledTotal":0
            })
        }

    });

}


function toggleTbody(tbodyelement, imgelementUp, imgElementDown){
    let tbody = document.getElementById(tbodyelement);
    let imgUp = document.getElementById(imgelementUp);
    let  imgDown = document.getElementById(imgElementDown);
    if (isHidden(tbody)){
        tbody.style.display = "block";
        imgUp.style.display = "block";
        imgDown.style.display = "none";
    }else{
        tbody.style.display = "none";
        imgUp.style.display = "none";
        imgDown.style.display = "block";
    }



}


const isHidden = elem => {
    const styles = window.getComputedStyle(elem)
    return styles.display === 'none' || styles.visibility === 'hidden'
}

function getKursWithHoursLeft(){
    var ret = null
    for (const key of kurser.keys()) {
        ret = key
        var kursen = kurser.get(key)
        var kvar = kursen.totalHours -  (kursen.scheduledTotal/60);
        if(kvar > 0)
            break;
    }
    return ret;
}

function recalculate(){
    totalSumma.innerText = summeraTotal()
    refreshKurserAttValja()
    if(selectedCourse === null){
        selectedCourseKey = getKursWithHoursLeft()
        onSelectKurs(selectedCourseKey)
    }


    let utbildarBody = document.getElementById('utbildare')
    utbildarBody.innerText = ''

    for (const key of utbildarMap.keys()){
        var tr = document.createElement('tr');
        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
        var text1 = document.createTextNode(key);
        var text2 = document.createTextNode(utbildarMap.get(key)/60);
        td1.appendChild(text1);
        td2.appendChild(text2);
        tr.appendChild(td1);
        tr.appendChild(td2);
        utbildarBody.appendChild(tr);
    }

    let kursBody = document.getElementById('kurs')
    kursBody.innerText = ''


    for (const key of kurser.keys()){
        if (kurser.get(key).scheduledCurrent === 0)
           continue;
        var tr = document.createElement('tr');
        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
        var text1 = document.createTextNode(key);
        var text2 = document.createTextNode(kurser.get(key).scheduledCurrent/60);
        td1.appendChild(text1);
        td2.appendChild(text2);
        tr.appendChild(td1);
        tr.appendChild(td2);
        kursBody.appendChild(tr);
    }


}


function selectKurs(){

    refreshKurserAttValja()


    dialog.style.left = card1.offsetLeft + "px";
    dialog.style.top = card1.offsetTop + "px";
    dialog.showModal()
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}

function onSelectKurs(kursKey){
    refreshKursData(kursKey)
    dialog.close()
}

function refreshKursData(kursKey) {
    var kursen = kurser.get(kursKey)
    selectedCourse = kursen
    selectedCourseKey = kursKey
    selectedCourseName.innerText = kursKey;
    selectedCourseStart.innerText =formatDate(kursen.startDate)
    selectedCourseEnd.innerText =formatDate(kursen.endDate)
    selectedCourseHoursTotal.innerText = kursen.totalHours
    var kvar = kursen.totalHours -  (kursen.scheduledTotal/60);
    selectedCourseHoursLeft.innerText = kvar

    ec.setOption('eventBackgroundColor',kursen.backgroundColor)
}


function refreshKurserAttValja(){
    kurserAttValja.innerText = ''


    for (const key of kurser.keys()) {
        var kursen = kurser.get(key)
        var kvar = kursen.totalHours -  (kursen.scheduledTotal/60);
        if(toggleAll.innerText === "visa alla" &&   (kvar <= 0) )continue;
        var tr = document.createElement('tr');

        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
        var td3 = document.createElement('td');
        var td4 = document.createElement('td');
        var td5 = document.createElement('td');
        var td6 = document.createElement('td');
        var td7 = document.createElement('td');
        var text1 = document.createTextNode(key);
        var text2 = document.createTextNode("kund");
        var text3 = document.createTextNode(formatDate(kursen.startDate));
        var text4 = document.createTextNode(formatDate(kursen.endDate));
        var text5 = document.createTextNode(kursen.totalHours);
        var text6 = document.createTextNode(kvar);
        //<a className="listbutton" href="1">Select</a>
        var button = document.createElement("a",)
        button.appendChild(document.createTextNode("Select"))
        button.classList.add("listbutton")
        button.href = "#";
        button.onclick = function(){onSelectKurs(key)}

        td1.appendChild(text1);
        td2.appendChild(text2);
        td3.appendChild(text3);
        td4.appendChild(text4);
        td5.appendChild(text5);
        td6.appendChild(text6);
        td7.appendChild(button)
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);
        tr.appendChild(td5);
        tr.appendChild(td6);
        tr.appendChild(td7)


        kurserAttValja.appendChild(tr);
    }

}

function summeraTotal(){
    utbildarMap.clear()
//    kurser.clear()
    let summa = 0;

    for (const key of kurser.keys()) {
        var kursen = kurser.get(key)
        kursen.scheduledTotal = 0
        kursen.scheduledCurrent = 0
    }

    ec.getEvents().forEach( function(ev){
        if(ev.id < 0) return
        var lengthInMinutes = (ev.end - ev.start) / 60000;
        if(!kurser.has(ev.title)){
            kurser.set(ev.title,{ "id": ev.extendedProps.kurs.id,
                "totalHours": ev.extendedProps.kurs.totalHours,
                "title":ev.title,
                "defaultTeacher": {
                    "id": ev.extendedProps.kurs.defaultTeacher.id,
                    "name": ev.extendedProps.kurs.defaultTeacher.name
                },
                "defaultLocation": ev.extendedProps.kurs.defaultLocation,
                "startDate": ev.extendedProps.kurs.startDate,
                "endDate": ev.extendedProps.kurs.endDate,
                "name": ev.extendedProps.kurs.name,
                "klassname": ev.extendedProps.klass.name,
                "klassid": ev.extendedProps.klass.id,
                "backgroundColor": ev.extendedProps.klass.color,
                "scheduledCurrent": 0,
                "scheduledTotal":0
            })
        }
        lengthInMinutes -= ev.extendedProps.lunch;
        kurser.get(ev.title).scheduledTotal += lengthInMinutes


        if(ev.start >= ec.getView().activeStart && ev.end <= ec.getView().activeEnd) {

            summa += lengthInMinutes;
            if(!utbildarMap.has(ev.extendedProps.teacher.name)){
                utbildarMap.set(ev.extendedProps.teacher.name,lengthInMinutes)
            }else{
                utbildarMap.set(ev.extendedProps.teacher.name, utbildarMap.get(ev.extendedProps.teacher.name) + lengthInMinutes)
            }

            kurser.get(ev.title).scheduledCurrent += lengthInMinutes
        }
    });
    return summa / 60
}


function newEventDialog(){
    ectitle = document.getElementsByClassName('ec-title')[0]
    eventDialog.style.left = ectitle.offsetLeft + "px";
    eventDialog.style.top = ectitle.offsetTop + "px";
    dialogKursName.innerText = selectedCourse.name
    dialogKlassName.value = selectedCourse.klassname
    dialogNotes.value = ''
    dialogLunch.value = '0';
    clearOptions(dialogLocation)
    addOption(dialogLocation,"0", "Välj")
    serverOptions.locations.forEach(c=>{
        addOption(dialogLocation,c.id, c.name)

    })
    if(selectedCourse.defaultLocation != null)
        dialogLocation.value = selectedCourse.defaultLocation.id


    clearOptions(dialogTeacher)
    addOption(dialogTeacher,"0", "Välj")
    serverOptions.teachers.forEach(c=>{
        addOption(dialogTeacher,c.id, c.name)

    })

    if(selectedCourse.defaultTeacher != null)
        dialogTeacher.value = selectedCourse.defaultTeacher.id

    eventDialog.showModal()
}



function editEventDialog(){
    ectitle = document.getElementsByClassName('ec-title')[0]
    eventDialog.style.left = ectitle.offsetLeft + "px";
    eventDialog.style.top = ectitle.offsetTop + "px";
    console.dir(editingInfo)
    dialogKursName.innerText = editingInfo.event.extendedProps.kurs.name
    dialogKlassName.value = editingInfo.event.extendedProps.klass.name
    dialogNotes.value = editingInfo.event.extendedProps.notes
    dialogLunch.value = editingInfo.event.extendedProps.lunch
    clearOptions(dialogLocation)
    addOption(dialogLocation,"0", "Välj")
    serverOptions.locations.forEach(c=>{
        addOption(dialogLocation,c.id, c.name)

    })
   if(editingInfo.event.extendedProps.location != null)
        dialogLocation.value = editingInfo.event.extendedProps.location.id


    clearOptions(dialogTeacher)
    addOption(dialogTeacher,"0", "Välj")
    serverOptions.teachers.forEach(c=>{
        addOption(dialogTeacher,c.id, c.name)

    })

    if(editingInfo.event.extendedProps.teacher!= null)
        dialogTeacher.value = editingInfo.event.extendedProps.teacher.id

    eventDialog.showModal()
}


function addOption(selectElement, value, text){
    var opt = document.createElement('option');
    opt.value = value;
    opt.innerHTML = text;
    selectElement.appendChild(opt);
}

function clearOptions(selectElement){
    while (selectElement.options.length > 0) {
        selectElement.remove(0);
    }
}

function ISO8601_week_no(dt)
{
    var date = new Date(dt.valueOf());
    date.setHours(0,0,0,0);

    // Thursday in current week decides the year.
    date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
    // January 4 is always in week 1.
    var week1 = new Date(date.getFullYear(), 0, 4);
    // Adjust to Thursday in week 1 and count number of weeks from date to week1.
    return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);



}
