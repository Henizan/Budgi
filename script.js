const hamburgerbutton = document.querySelector(".nav-toggler")
const navigation = document.querySelector("nav")

hamburgerbutton.addEventListener("click", toggleNav)

function toggleNav(){
    hamburgerbutton.classList.toggle("active")
    navigation.classList.toggle("active")
}