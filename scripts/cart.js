async function addToCart(id){
    const antal = await((await fetch(`/addtocart?id=${id}`)).text())
    console.log(antal)
    document.getElementById("antal").innerText = antal
}

