Given(/^I am about to log out$/) do

	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end


When(/^I click on the log out button$/) do
	expect(current_path).to eq '/'
	#page.find('#show-side').click()
	#expect(page).to have_selector('.highcharts-series.highcharts-series-0', visible: 'null')
	page.find('.toggle-drop.profile.icon.ion-person').click()
	page.find('.dropitem.logout').click()
end

Then(/^the page goes back to login page$/) do
	expect(current_path).to eq '/login/' 

end
