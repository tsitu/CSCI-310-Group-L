Given (/^user is on UI$/) do
<<<<<<< HEAD
	visit("http://localhost:80")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
=======
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc
end

When(/^user clicks the account button$/) do
	if(page.has_button?('add-account'))
		page.click_button('add-account')
	end
end

Then(/^user can create new account$/) do
<<<<<<< HEAD
	expect(page).to have_selector('#new-account-diaglog', visible: true)
=======
	expect(page).to have_selector('#new-account-dialog', visible: true)
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc
end
