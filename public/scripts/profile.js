$(document).ready(function () {
	// Обработчик для кнопки "LOG OUT"
	$('#confirmLogout').on('click', function () {
		// Выполняем выход из системы
		$.ajax({
			url: '../logout.php',
			method: 'POST',
			dataType: 'json',
			success: function (response) {
				if (response.status === 'success') {
					window.location.href = 'register.php'
				} else {
					alert('Ошибка при выходе из системы')
				}
			},
			error: function () {
				alert('Ошибка при выходе из системы')
			},
		})
	})

	// Обработчик для кнопки "CHANGE PASSWORD"
	$('.profile-option.change-password').on('click', function () {
		var changePasswordModal = new bootstrap.Modal(
			document.getElementById('changePasswordModal')
		)
		changePasswordModal.show()
	})

	// Обработка формы изменения пароля
	$('#changePasswordForm').on('submit', function (e) {
		e.preventDefault()
		var formData = $(this).serialize() + '&action=change_password'

		$.ajax({
			url: '../Profile_Script.php',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function (response) {
				alert(response.message)
				if (response.status === 'success') {
					var changePasswordModal = bootstrap.Modal.getInstance(
						document.getElementById('changePasswordModal')
					)
					changePasswordModal.hide()
				}
			},
			error: function () {
				alert('Произошла ошибка при изменении пароля.')
			},
		})
	})

	// Обработчик для кнопки "CHANGE EMAIL"
	$('.profile-option.change-email').on('click', function () {
		var changeEmailModal = new bootstrap.Modal(
			document.getElementById('changeEmailModal')
		)
		changeEmailModal.show()
	})

	// Обработка формы изменения email
	$('#changeEmailForm').on('submit', function (e) {
		e.preventDefault()
		var formData = $(this).serialize() + '&action=change_email'

		$.ajax({
			url: '../Profile_Script.php',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function (response) {
				alert(response.message)
				if (response.status === 'success') {
					var changeEmailModal = bootstrap.Modal.getInstance(
						document.getElementById('changeEmailModal')
					)
					changeEmailModal.hide()
				}
			},
			error: function () {
				alert('Произошла ошибка при изменении email.')
			},
		})
	})

	// Обработчик для кнопки "CHANGE PHONE NUMBER"
	$('.profile-option.change-phone').on('click', function () {
		var changePhoneModal = new bootstrap.Modal(
			document.getElementById('changePhoneModal')
		)
		changePhoneModal.show()
	})

	// Обработка формы изменения номера телефона
	$('#changePhoneForm').on('submit', function (e) {
		e.preventDefault()
		var formData = $(this).serialize() + '&action=change_phone'

		$.ajax({
			url: '../Profile_Script.php',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function (response) {
				alert(response.message)
				if (response.status === 'success') {
					var changePhoneModal = bootstrap.Modal.getInstance(
						document.getElementById('changePhoneModal')
					)
					changePhoneModal.hide()
				}
			},
			error: function () {
				alert('Произошла ошибка при изменении номера телефона.')
			},
		})
	})

	// Обработчик для кнопки "CHANGE PERSONAL INFORMATION"
	$('.profile-option.change-info').on('click', function () {
		var changeInfoModal = new bootstrap.Modal(
			document.getElementById('changeInfoModal')
		)
		changeInfoModal.show()
	})

	// Обработка формы изменения личной информации
	$('#changeInfoForm').on('submit', function (e) {
		e.preventDefault()
		var formData = $(this).serialize() + '&action=change_info'

		$.ajax({
			url: '../Profile_Script.php',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function (response) {
				alert(response.message)
				if (response.status === 'success') {
					var changeInfoModal = bootstrap.Modal.getInstance(
						document.getElementById('changeInfoModal')
					)
					changeInfoModal.hide()
					// Обновляем отображаемое имя пользователя
					$('#profile-name').text(
						$('input[name="new_username"]').val() +
							' ' +
							$('input[name="new_lastname"]').val()
					)
					// Возможно, вам нужно обновить и другие элементы, если они отображают логин или пол
				}
			},
			error: function () {
				alert('Произошла ошибка при изменении информации.')
			},
		})
	})

	// Обработка изменения аватара
	$('#avatarForm').on('submit', function (e) {
		e.preventDefault()

		var formData = new FormData(this)
		formData.append('action', 'change_avatar')

		$.ajax({
			url: '../Profile_Script.php',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function (response) {
				if (response.status === 'success') {
					// Обновляем аватарку на странице
					$('#avatarImage').attr(
						'src',
						'display_avatar.php?' + new Date().getTime()
					)
					// Закрываем модальное окно
					var avatarModal = bootstrap.Modal.getInstance(
						document.getElementById('avatarModal')
					)
					avatarModal.hide()
				} else {
					alert('Ошибка: ' + response.message)
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Произошла ошибка при загрузке аватара: ' + textStatus)
			},
		})
	})
})

