const tablerows = document.getElementById("tablerows");

async function fetchAll(){
    return await((await fetch('/json/popular')).json())
}

const createTableTdOrTh = function(elementType, txt){
    let element = document.createElement(elementType)
    element.textContent = txt
    return element 
}

const showTable = function(customersArray){
    tablerows.innerHTML = ""
    customersArray.forEach(customer => {
        let tr = document.createElement("tr")
        tr.appendChild(createTableTdOrTh("th",customer.GivenName + ' ' + customer.Surname))
        tr.appendChild(createTableTdOrTh("th",customer.City))
        tr.appendChild(createTableTdOrTh("th",customer.Country))
        
        let td = document.createElement("td")
        let btn = document.createElement("button")
        btn.textContent = "EDIT"
        btn.dataset.stefansplayerid=customer.Id
        td.appendChild(btn)
        tr.appendChild(td)
                 
        tablerows.appendChild(tr)
    });

}

let data =  await fetchAll()
showTable(data)