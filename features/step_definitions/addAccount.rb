Given(/^user has an account csv file$/) do 
	Capybara.reset_sessions!
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	#page.execute_script("$('.show-side toggle-side fa fa-bars').click()")
	list = Array.new
	list = find('#account-list').all('li')
	@size = list.size
	
end 

When(/^user adds it through browse$/) do 
	#page.find('#show-side').click()
	page.find('#toggle-upload').click()
	#page.execute_script("$('.dyn-crm-upload-btn-container').css('display','block')")
	#Capybara.ignore_hidden_elements = false
	attach_file('csv-file', File.absolute_path('../samples/test.csv'))
	#Capybara.ignore_hidden_elements = false
	page.find('#csv-upload').click()
end 

Then(/^account list increases$/) do 
	expect(find('#account-list')).to have_selector('li', count: @size + 1)
end 
