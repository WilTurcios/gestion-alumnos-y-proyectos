data = fetch('http://localhost/proyecto-DAW/backend/api/estudiantes')
	.then(res => {
		console.log('Status:', res.status)
		console.log('Status Text:', res.statusText)
		return res.text() // Cambiar a res.text() para ver el contenido bruto de la respuesta
	})
	.then(text => {
		console.log('Response Text:', text) // Imprime el contenido de la respuesta
		return JSON.parse(text) // Parsear manualmente el texto a JSON
	})
	.then(({ data }) => {
		console.log(data)
		return data
	})
	.catch(error => {
		console.error('There was a problem with the fetch operation:', error)
	})
