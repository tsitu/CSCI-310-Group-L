Given(/^user clicks on the account and transactions$/) do
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end

Then(/^user should be able to see a graph$/) do
	expect(page).to have_selector('#graph', visible:true)
	page.should have_css('svg')
end

