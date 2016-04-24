Given (/^user is on UI$/) do
	Capybara.reset_sessions!
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end

When(/^user clicks the account button$/) do
	page.find('#show-side').click()
end

Then(/^user can create new account$/) do
	page.execute_script("$('add-toggle').click()")
	page.find('#csv-label')
end
