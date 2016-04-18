Given(/^user is on the login page$/) do
<<<<<<< HEAD
	visit("http://localhost:80")
=======
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc
end

When(/^user types the right password and username$/) do
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
<<<<<<< HEAD
	page.execute_script("$('button.login-button').click()")
end

Then(/^user can login$/)do
	expect(current_path).to eq '/'
=======
	page.execute_script("$('#login-button').click()")
end

Then(/^user can login$/)do
	expect(current_path).to eq '/CSCI-310-Group-L/www/'
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc
end 
