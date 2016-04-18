Given(/^I am on the main UI$/) do
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end



Then(/^I should see a user menu$/) do
	page.has_content?('user-menu')
	expect(page).to have_selector('#user-menu', visible: true)
end 
