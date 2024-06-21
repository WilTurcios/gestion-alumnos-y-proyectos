export function getStudents() {
	return fetch('http://localhost/proyecto-DAW/backend/api/estudiantes')
		.then(res => res.json())
		.then(data => data)
}

export function getStudentById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/estudiantes/${id}`)
		.then(res => res.json())
		.then(data => data)
}

export function deleteStudentById(id) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/estudiantes/${id}`, {
		method: 'DELETE'
	})
		.then(res => res.json())
		.then(data => {
			let [estudiante] = data
			return estudiante
		})
}

export function addStudent(student) {
	return fetch(`http://localhost/proyecto-DAW/backend/api/estudiantes`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(student)
	})
		.then(res => res.json())
		.then(data => data)
}
