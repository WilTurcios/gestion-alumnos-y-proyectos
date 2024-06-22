export function getStudents() {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch('http://localhost/proyecto-DAW/backend/api/estudiantes', {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function getStudentById(id) {
	const token = localStorage.getItem('token')
	return fetch(`http://localhost/proyecto-DAW/backend/api/estudiantes/${id}`, {
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => data)
}

export function deleteStudentById(id) {
	const token = localStorage.getItem('token')
	return fetch(`http://localhost/proyecto-DAW/backend/api/estudiantes/${id}`, {
		method: 'DELETE',
		headers: {
			Authorization: `Bearer ${token}`
		}
	})
		.then(res => res.json())
		.then(data => {
			let [estudiante] = data
			return estudiante
		})
}

export function addStudent(student) {
	const token = localStorage.getItem('token')
	return fetch(`http://localhost/proyecto-DAW/backend/api/estudiantes`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			Authorization: `Bearer ${token}`
		},
		body: JSON.stringify(student)
	})
		.then(res => res.json())
		.then(data => data)
}
export function updateStudent(student) {
	const token = JSON.parse(localStorage.getItem('token'))
	return fetch(`http://localhost/proyecto-DAW/backend/api/estudiantes`, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/json',
			Authorization: `Bearer ${token}`
		},
		body: JSON.stringify(student)
	})
		.then(res => res.json())
		.then(data => data)
}
