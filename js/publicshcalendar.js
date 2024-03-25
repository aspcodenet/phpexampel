

var fetchEvents = true // Set to true whenever new fetch is needed
var selectedClassElement = document.getElementById('selectedClass')
var selectedTeacherElement = document.getElementById('selectedTeacher')
const weekNo = document.getElementById('weekNo')
const prenumereraIcal = document.getElementById('prenumereraIcal')
const icalLink = document.getElementById('icalLink')
const showIcal = document.getElementById('showIcal')
const currentOrgId  = document.getElementById('currentOrgId')
const urlParams = new URLSearchParams(window.location.search);

const utbildarMap = new Map()

const kurser = new Map()


selectedClassElement.addEventListener('change', ()=>{
   filterEvents();
})

selectedTeacherElement.addEventListener('change', ()=>{
    filterEvents();
})

prenumereraIcal.addEventListener('click',e=>{
    e.preventDefault()
    icalLink.select()
    icalLink.setSelectionRange(0,99999)
    navigator.clipboard.writeText(icalLink.value);
    if(showIcal.style.display === "none") {
        showIcal.style.display = "block"
    }else{
        showIcal.style.display = "none"
    }


})

let serverOptions = null

let ec = new EventCalendar(document.getElementById('ec'), {
    datesSet: function (info){
        vecka = ISO8601_week_no(info.start)
        weekNo.innerText = 'Vecka ' + vecka
    },
    view: 'timeGridWeek',
    height: '800px',
    eventClick:function (info){
        editingInfo = info
        showEventDialog()
    },
    headerToolbar: {
        start: 'prev,next today',
        center: 'title',
        end: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    buttonText: function (texts) {
        texts.listWeek = 'lista';
        texts.dayGridMonth = 'månad';
        texts.timeGridWeek = 'vecka';
        texts.timeGridDay = 'dag';
        return texts;
    },
    resources: [
    ],
    scrollTime: '07:00:00',
    slotMinTime:'07:00:00',
    slotMaxTime:'20:00:00',
    plugins: [],
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

    views: {
        timeGridWeek: {pointer: true},
        resourceTimeGridWeek: {pointer: true}
    },
    dayMaxEvents: true,
    nowIndicator: true,
    selectable: false,
    eventDurationEditable:false,
    eventStartEditable:false,
    editable:false
});


refreshOptions(ec)



function loadEvents(fetchInfo, successCallback, failureCallback) {
    // if(!fetchEvents) {
    //     recalculate()
    //     return
    // }
    const allParam = urlParams.get('all');

    fetch("/public/" + currentOrgId.value + "/api/events?cb=" + Math.random() + "&k=" + selectedClassElement.value +"&t="  + selectedTeacherElement.value + "&end=" +fetchInfo.end.toISOString() + "&start=" +  fetchInfo.start.toISOString() + "&all=" + allParam)
        .then(response => response.json())
        .then(json => {
            fetchEvents = false
            successCallback(json);
            recalculate()
            var url  = new URL("/public/" + currentOrgId.value + "/ical?k=" + selectedClassElement.value + "&t=" +  selectedTeacherElement.value + '&all=' + allParam, window.location.href);
            icalLink.value =  url
            //ec.setOption('resources',serverOptions.resources)
        });
}


function loadHolidays(fetchInfo, successCallback, failureCallback) {
    // if(!fetchEvents) {
    //     recalculate()
    //     return
    // }
    fetch("/public/api/holidays?cb=" + Math.random() + "&end=" +fetchInfo.end.toISOString() + "&start=" +  fetchInfo.start.toISOString())
        .then(response => response.json())
        .then(json => {
            successCallback(json);
        });
}




function refreshOptions(ec){
    const allParam = urlParams.get('all');

    fetch("/public/" + currentOrgId.value + "/api/options?cb=" + Math.random() + "&all=" + allParam)
        .then(response=>response.json())
        .then(json=>{
            serverOptions =  { resources: [], teachers:[] }
            serverOptions.resources = json.rooms.map(m=>{ return {id:m.id, title:m.name }})
            serverOptions.teachers = json.teachers
            serverOptions.locations = json.locations
            ec.setOption('resources',serverOptions.resources)
        });
}

const isHidden = elem => {
    const styles = window.getComputedStyle(elem)
    return styles.display === 'none' || styles.visibility === 'hidden'
}


function recalculate(){
    // totalSumma.innerText = summeraTotal()
    // refreshKurserAttValja()
    // if(selectedCourse === null){
    //     selectedCourseKey = getKursWithHoursLeft()
    //     onSelectKurs(selectedCourseKey)
    // }
    //
    //
    // let utbildarBody = document.getElementById('utbildare')
    // utbildarBody.innerText = ''
    //
    // for (const key of utbildarMap.keys()){
    //     var tr = document.createElement('tr');
    //     var td1 = document.createElement('td');
    //     var td2 = document.createElement('td');
    //     var text1 = document.createTextNode(key);
    //     var text2 = document.createTextNode(utbildarMap.get(key)/60);
    //     td1.appendChild(text1);
    //     td2.appendChild(text2);
    //     tr.appendChild(td1);
    //     tr.appendChild(td2);
    //     utbildarBody.appendChild(tr);
    // }
    //
    // let kursBody = document.getElementById('kurs')
    // kursBody.innerText = ''
    //
    //
    // for (const key of kurser.keys()){
    //     if (kurser.get(key).scheduledCurrent === 0)
    //        continue;
    //     var tr = document.createElement('tr');
    //     var td1 = document.createElement('td');
    //     var td2 = document.createElement('td');
    //     var text1 = document.createTextNode(key);
    //     var text2 = document.createTextNode(kurser.get(key).scheduledCurrent/60);
    //     td1.appendChild(text1);
    //     td2.appendChild(text2);
    //     tr.appendChild(td1);
    //     tr.appendChild(td2);
    //     kursBody.appendChild(tr);
    // }
    //

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




function showEventDialog(){
    ectitle = document.getElementsByClassName('ec-title')[0]
    eventDialog.style.left = ectitle.offsetLeft + "px";
    eventDialog.style.top = ectitle.offsetTop + "px";
    dialogKursName.innerText = selectedCourse.name
    dialogKlassName.value = selectedCourse.klassname
    dialogNotes.value = editingInfo.event.extendedProps.notes
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



function filterEvents(){
    ec.refetchEvents()
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
