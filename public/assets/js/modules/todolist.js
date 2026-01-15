const inputForm = document.getElementById("todoInput")
// Inline JavaScript for this page
document.addEventListener('DOMContentLoaded', function() {
		// Add todo button click handler
		document.getElementById('addTodoBtn').addEventListener('click', function() {
			 
				// You can implement a modal or form here
		});
		
		// Todo checkbox toggle
		document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
				checkbox.addEventListener('change', function() {
						const taskText = this.closest('tr').querySelector('span');
						if (this.checked) {
								taskText.classList.add('line-through', 'text-text-secondary');
						} else {
								taskText.classList.remove('line-through', 'text-text-secondary');
						}
				});
		});
});

document.querySelectorAll('[data-editable]').forEach(item => {
	const input = item.querySelector('.edit-input')
	const sizer = item.querySelector('.input-sizer')

	const resize = () => {
		sizer.textContent = input.value || ' '
		input.style.width = sizer.offsetWidth + 2 + 'px'
	}
	item.addEventListener('click', () => {
		const groupId = item.dataset.id
		getAllTask(groupId)
	})
	resize()

	input.addEventListener('input', resize)

	// DOUBLE CLICK → EDIT
	item.addEventListener('dblclick', () => {
		item.classList.add('editing')
		input.removeAttribute('readonly')
		input.focus()
		input.select()
	})

	const save = async () => {
		const value = input.value.trim()
		const id = item.dataset.id
		console.log(item.dataset, item.getAttribute("data-id"))
		// XOÁ HẾT → DELETE
		if (value === '') {
			await deleteGroup(id)
			item.remove()
			return
		}

		input.value = value
		input.setAttribute('readonly', true)
		item.classList.remove('editing')
		resize()

		await updateGroup(id, value)
	}

	input.addEventListener('blur', save)

	input.addEventListener('keydown', e => {
		if (e.key === 'Enter') input.blur()
		if (e.key === 'Escape') {
			input.value = input.defaultValue
			input.blur()
		}
	})
})

function openTodoModal() {
	document.getElementById("addTodoModal").classList.remove("hidden");
	document.getElementById("todoInput").focus();
}

function closeTodoModal() {
	document.getElementById("addTodoModal").classList.add("hidden");
	inputForm.value = ''
}

function saveTodo() {
	const value = document.getElementById("todoInput").value.trim();
	if (!value) return;

	alert("New group: " + value); // sau này thay bằng AJAX
	closeTodoModal();
}


function renderGroup(group) {
	const ul = document.getElementById("groupList");
	
	const li = document.createElement("li");
	li.className = "card-list-item";
	li.dataset.id = group.group_id || group.todolist_id;
	li.setAttribute("data-editable", "");
	
	li.innerHTML = `
	<input
	class="edit-input"
	type="text"
	value="${group.title}"
	readonly
		/>
		<span class="input-sizer"></span>
		`;
		
		ul.appendChild(li);
		
		// ====== INLINE EDIT LOGIC ======
		const input = li.querySelector(".edit-input");
		const sizer = li.querySelector(".input-sizer");
		
		const resize = () => {
			sizer.textContent = input.value || " ";
			input.style.width = sizer.offsetWidth + 2 + "px";
		};
		
		resize();
		
		input.addEventListener("input", resize);
		
		// Double click → edit
		li.addEventListener("dblclick", () => {
			li.classList.add("editing");
		input.removeAttribute("readonly");
		input.focus();
		input.select();
	});
	
	const save = async () => {
		const value = input.value.trim();
		const id = li.dataset.id;
		
		// Xoá hết chữ → delete
		if (value === "") {
			input.blur() 
			input.onblur = null
			const ok = await deleteGroup(id);
			if (ok) item.remove()
			return;
		}
		
		input.value = value;
		input.setAttribute("readonly", true);
		li.classList.remove("editing");
		resize();
		
		await updateGroup(id, value);
	};
	
	input.addEventListener("blur", save);
	
	input.addEventListener("keydown", (e) => {
		if (e.key === "Enter") input.blur();
		if (e.key === "Escape") {
			input.value = input.defaultValue;
			input.blur();
		}
	});
}

async function createTodo() {
		

		const title = inputForm.value.trim();
		if (!title) {
				return showToast("Tên group không được để trống")
		}
		const res = await fetch ("/todo/api/createGroup", {
				method: "POST",
				headers: {
						"Content-Type": "application/x-www-form-urlencoded"
				},
				body:   "action=createGroup&title=" + encodeURIComponent(title)
				
		})

		// const data = await res.json();
		let data;
		try {
				data = await res.json();
		} catch (e) {
				console.error("Server returned non-JSON");
				console.log(await res.text());
				return;
		}
		if (data.success) {
				renderGroup(data.group);
				closeTodoModal();
		} else {
				showToast(data.error || "Error")
		}
}

async function updateGroup(id,title) {
	const res = await fetch ("/todo/api/updateGroup", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded"

		},
		body: `action=updateGroup&groupId=`+encodeURIComponent(id)+`&title=`+encodeURIComponent(title)
	})
	let data;
	try {
		data=await res.json();
	} catch (e) {
		console.error("Server returned non-JSON");
		console.log(await res.text());
		return;
	}

	if (data.success) {
		renderGroup(data.group) 
	} else {
		showToast(data.error || "Error")
	}
}

async function deleteGroup(id) {
	const res = await fetch ("/todo/api/deleteGroup", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded"

		},
		body: `action=deleteGroup&groupId=`+encodeURIComponent(id)
	})
	let data;
	try {
		data=await res.json();
	} catch (e) {
		console.error("Server returned non-JSON");
		console.log(await res.text());
		return;
	}

	if (data.success) {
		return data.success === true
	} else {
		showToast(data.error || "Error")
	}
}

async function getAllTask(id) {
	const res = await fetch ("/todo/api/task", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded"

		},
		body: `action=getAllTask&groupId=`+encodeURIComponent(id)
	})
	let data;
	try {
		data=await res.json();
	} catch (e) {
		console.error("Server returned non-JSON");
		console.log(await res.text());
		return;
	}

	if (data.success) {
		renderAllTask(data.AllTask) 
	} else {
		showToast(data.error || "Error")
	}
}

function renderAllTask(tasks) {
	const tbody = document.querySelector("table tbody")
	tbody.innerHTML = ""

	tasks.forEach(todo => {
		const tr = document.createElement("tr")
		tr.className = "border-b border-border hover:bg-bg-input"

		const priorityClass = {
			high: "badge badge-strong",
			medium: "badge badge-medium",
			low: "badge badge-soft"
		}[todo.priority] || "badge"

		const statusClass = {
			pending: "badge badge-soft",
			in_progress: "badge badge-medium",
			completed: "badge badge-strong"
		}[todo.status] || "badge"

		tr.innerHTML = `
			<td class="p-4">
				<div class="flex items-center">
					<input type="checkbox" class="mr-3 h-5 w-5 rounded border-border" ${todo.status === "completed" ? "checked" : ""}>
					<span class="${todo.status === "completed" ? "line-through text-text-secondary" : ""}">
						${todo.task}
					</span>
				</div>
			</td>
			<td class="p-4">
				<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${priorityClass}">
					${todo.priority}
				</span>
			</td>
			<td class="p-4 text-text">${todo.due_date}</td>
			<td class="p-4">
				<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
					${todo.status.replace("_", " ")}
				</span>
			</td>
			<td class="p-4">
				<div class="flex space-x-2">...</div>
			</td>
		`

		tbody.appendChild(tr)
	})
}
