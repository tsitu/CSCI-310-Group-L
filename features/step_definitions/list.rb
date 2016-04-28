Given(/^user is on the main UI$/) do
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end



Then(/^user should see a list$/) do
	expect(current_path).to eq '/'
	expect(page).to have_selector('#account-list')
end
