Given(/^user is on the main UI$/) do
	visit("http://localhost:80")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
end

Then(/^user should see a list$/) do
	expect(page).to have_selector('#account-module')
end
