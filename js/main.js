 const ROOT = "http://localhost/Wow-Food/"

/* components */
new class NavUpdate {
    constructor () {
        this.__toggle () 
    }
    __toggle () {
        document.addEventListener ("click", e => {
            const node = e.target
            const allParent = document.querySelectorAll (".nav-update")
            const parent = node.closest(".nav-update")

            allParent.forEach(el => {
            if (el !== parent) 
               el.classList.remove ("active")
            else
                parent.classList.toggle ("active")
           }) 
        })
    }
}
const Modal = new class Modal {
    btns = document.querySelectorAll (".modal-btn")
    constructor () {
        this.__toggle () 
    }
    __toggle () {
        document.addEventListener ("click", e => {
            const node = e.target
            const allMadals = document.querySelectorAll (".modal")
            const allactiveMadals = document.querySelectorAll (".modal.active")
            const btn = node.closest(".modal-btn")
            const content = node.closest(".modal-content")
            
            if (btn) {
                const id = btn.getAttribute("data-modal-id")
                allMadals.forEach (el => {
                    if (el.hasAttribute("data-modal-id"))
                        if (el.getAttribute("data-modal-id") === id)
                            el.classList.toggle ("active")
                })
            } else 
                if (!content) 
                    allactiveMadals.forEach(el => el.classList.remove ("active")) 
        })
    }
    close () {
        const allactiveMadals = document.querySelectorAll (".modal.active")
        allactiveMadals.forEach(el => el.classList.remove ("active")) 
    }
}
const Form = new class Form {
    forms = document.querySelectorAll (".form")
    madals = document.querySelectorAll (".modal")
    constructor () {
        this.__animatePlaceholder () 
        this.__loadAnimatePlaceholder ()
    }
    __loadAnimatePlaceholder () {
        this.forms.forEach (form => {
            const labels = form.querySelectorAll ("label")
            labels.forEach (label => {
                
                let input = label.querySelector("input[type='checkbox']")
                if (input) {
                    if (input.checked)
                        label.classList.add("active")
                    return
                }

                input = label.querySelector("input") || label.querySelector("textarea")
                if (input.value.trim() !== "")
                    label.classList.add("active")
            })
        })
    }
    __animatePlaceholder () {
        this.forms.forEach (form => {
            const labels = form.querySelectorAll ("label")
            labels.forEach (label => {
                let input = label.querySelector("input[type='checkbox']")
                if (input) {
                    input.addEventListener("change", e => {
                        if (input.checked)
                            label.classList.add("active")
                        else
                            label.classList.remove("active")
                    })
                }

                input = label.querySelector("input") || label.querySelector("textarea")
                input.addEventListener("focus", e => {
                    label.classList.add("active")
                })
                input.addEventListener("blur", e => {
                    if (input.value.trim() === "")
                        label.classList.remove("active")
                })
            })
        })
    }
    clear () {
        this.forms.forEach (form => {
            const labels = form.querySelectorAll ("label")
            labels.forEach (label => {
                const input = label.querySelector("input") || label.querySelector("textarea")
                input.value = ""
            })
        })
    }
}
class Animate {
    static remove (node) {
        node.style.opacity = 0
        node.style.transition = ".5s"
        node.addEventListener ("transitionend", e => {
            node.remove()
        })
    }
    static redirect (path) {
        setTimeout(() => {
            window.location.href = ROOT.concat("admin", path)
        }, "1s");
    }
}


/* users */
async function addUser (e) {
    try {
        e.preventDefault ()

        const messageInfo = document.querySelector(".message-info")
        const node = e.target
        const parent = node.closest(".modal")
        const table = document.querySelector(".table-full table tbody")
        const name = parent.querySelector("input[name='name']").value.trim()
        const userName = parent.querySelector("input[name='username']").value.trim()
        const password = parent.querySelector("input[name='password']").value.trim()
        const confirmpassword = parent.querySelector("input[name='confirmpassword']").value.trim()
        const message = parent.querySelector(".message")
        const formData = new FormData ()

        formData.append("add-user", true)
        formData.append("name", name)
        formData.append("username", userName)
        formData.append("password", password)

        let errors = []

        if (name === "")
            errors.push("Name field is required!") 
        else if (userName === "")
            errors.push("User Name field is required!") 
        else if (password === "") 
            errors.push("Password field is required!") 
        else if (await existUser(userName)) 
            errors.push("Username must be unique!") 
        else if (password !== confirmpassword) 
            errors.push("Passwords do not match!") 

        if (errors.length === 0) {
            errors = []
            message.classList.remove("active")

            $.ajax ({
                url: ROOT.concat("ajax/user.php"),
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (data) {
                    // console.log(data)
                    if  (!data || !data.includes ("status")) return
                    const result = JSON.parse(data)
                    console.log(result)

                    table.insertAdjacentHTML ("beforeend", result.data)
                    Modal.close ()
                    Form.clear ()

                    messageInfo.innerHTML = result.message
                    messageInfo.classList.add("active")
                },
                error: (jqXHR, textStatus, errorThrow) => {
                    console.log(jqXHR, textStatus, errorThrow)
                }
            })
        } else {
            message.innerHTML = errors[0]
            message.classList.add("active")
        }
    } catch (err) {
        console.log("addUser: " + err)
    }
}
function deleteUser (e, userId) {
    try {
        e.preventDefault ()

        const messageInfo = document.querySelector(".message-info")
        const node = e.target
        const parent = node.closest("tr")
        const formData = new FormData ()

        formData.append("delete-user", true)
        formData.append("userId", userId)

        $.ajax ({
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            url: ROOT.concat("ajax/user.php"),
            success: data => {
                const result = JSON.parse(data)
                console.log(result)
 
                messageInfo.innerHTML = result.message
                messageInfo.classList.add("active")

                Animate.remove (parent)
            },
            error: (jqXHR, textStatus, errorThrow) => {
                console.log(jqXHR, textStatus, errorThrow)
            }
        })
    } catch (err) {
        console.log("deleteUser: ", err)
    }
}
async function existUser (username) {
    try {
        const formData = new FormData ()

        formData.append("exist-user", true)
        formData.append("username", username)

        let result = false
    
        await $.ajax ({
            url: ROOT.concat("ajax/user.php"),
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            success: data => {
                // console.log(data)
                if  (!data || !data.includes ("status")) return

                const res = JSON.parse(data)
                console.log(res) 
                if (res.status === "error") {
                    result = true
                }
            },
            error: (jqXHR, textStatus, errorThrow) => {
                console.log(jqXHR, textStatus, errorThrow)
            }
        })

        return result
    } catch  (err) {
        console.log(err)
    }
}

/* category */
async function addCategory (e) {
    try {
        e.preventDefault ()

        const messageInfo = document.querySelector(".message-info")
        const node = e.target
        const parent = node.closest(".modal")
        const table = document.querySelector(".table-full table tbody")
        const title = parent.querySelector("input[name='title']").value.trim()
        const file = parent.querySelector("input[name='image_name']").files[0]
        const featured = parent.querySelector("input[name='featured']").checked ? 1 : 0
        const active = parent.querySelector("input[name='active']").checked ? 1 : 0
        const message = parent.querySelector(".message")
        const formData = new FormData ()

        const fileName = file?.name && ".".concat(file.name.split(".").pop())

        formData.append("add-category", true)
        formData.append("title", title)
        formData.append("file", file)
        formData.append("fileName", fileName)
        formData.append("featured", featured)
        formData.append("active", active)

        let errors = []

        if (title === "")
            errors.push("Title field is required!") 
        else if (!fileName)
            errors.push("Image field is required!") 
        else if (await existCategory(title)) 
            errors.push("Category must be unique!") 

        if (errors.length === 0) {
            errors = []
            message.classList.remove("active")

            $.ajax ({
                url: ROOT.concat("ajax/category.php"),
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function (data) {
                    // console.log(data)
                    // return
                    if  (!data || !data.includes ("status")) return
                    const res = JSON.parse(data)
                    console.log(res)    

                    table.insertAdjacentHTML ("beforeend", res.data)
                    Modal.close ()
                    Form.clear ()

                    messageInfo.innerHTML = res.message
                    messageInfo.classList.add("active")
                },
                error: (jqXHR, textStatus, errorThrow) => {
                    console.log(jqXHR, textStatus, errorThrow)
                }
            })
        } else {
            message.innerHTML = errors[0]
            message.classList.add("active")
        }
    } catch (err) {
        console.log("addCategory: " + err)
    }
}
function deleteCategory (e, categoryId) {
    try {
        e.preventDefault ()

        const messageInfo = document.querySelector(".message-info")
        const node = e.target
        const parent = node.closest("tr")
        const formData = new FormData ()

        formData.append("delete-category", true)
        formData.append("categoryId", categoryId)

        $.ajax ({
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            url: ROOT.concat("ajax/category.php"),
            success: data => {
                // console.log(data)
                if  (!data || !data.includes ("status")) return

                const res = JSON.parse(data)
                console.log(res) 
 
                messageInfo.innerHTML = res.message
                messageInfo.classList.add("active")

                Animate.remove (parent)
            },
            error: (jqXHR, textStatus, errorThrow) => {
                console.log(jqXHR, textStatus, errorThrow)
            }
        })
    } catch (err) {
        console.log("deleteUser: ", err)
    }
}
async function existCategory (title) {
    try {
        const formData = new FormData ()

        formData.append("exist-category", true)
        formData.append("title", title)

        let result = false
    
        await $.ajax ({
            url: ROOT.concat("ajax/category.php"),
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            success: data => {
                // console.log(data)
                if  (!data || !data.includes ("status")) return

                const res = JSON.parse(data)
                console.log(res) 
                if (res.status === "error") {
                    result = true
                }
            },
            error: (jqXHR, textStatus, errorThrow) => {
                console.log(jqXHR, textStatus, errorThrow)
            }
        })

        return result
    } catch  (err) {
        console.log("existCategory: " + err)
    }
}