Given (/^user is on UI$/) do
	visit("http://localhost:80")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end

When(/^user clicks the account button$/) do
	if(page.has_button?('add-account'))
		page.click_button('add-account')
	end
end

Then(/^user can create new account$/) do
	expect(page).to have_selector('#new-account-diaglog', visible: true)
	expect(page).to have_selector('#new-account-dialog', visible: true)
end
