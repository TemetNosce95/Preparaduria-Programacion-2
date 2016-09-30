/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GameFrame;

import static GameFrame.GameFrame.rnd;
import java.awt.Graphics;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.net.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JFrame;
import static javax.swing.JFrame.EXIT_ON_CLOSE;
import javax.swing.JOptionPane;
import javax.swing.JPanel;

/**
 *
 * @author geiver.botello
 */
public class ClientFrame extends JFrame implements Conexion{
    Ball ball;
    Raqueta r1,r2;
    int puntos1,puntos2;
    ClientFrame auto = this;
    
    Socket cliente;
    ObjectInputStream ois;
    boolean conectado;
    
    public ClientFrame() {
        this.setVisible(true);
        this.setBounds(50, 50, 500, 350);
        this.setDefaultCloseOperation(EXIT_ON_CLOSE);
        
        
        this.ball = new Ball(getWidth()/2,getHeight()/2,(rnd.nextInt()%2==0)?1:-1,(rnd.nextInt()%2==0)?1:-1);
        this.r1 = new Raqueta(0,getHeight()/2-Raqueta.altoR/2);
        this.r2 = new Raqueta(getWidth()-Raqueta.anchoR,getHeight()/2-Raqueta.altoR/2);
        
        JPanel panel = new JPanel(){

            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g); //To change body of generated methods, choose Tools | Templates.
                g.fillRect(r1.x,r1.y,r1.anchoR,r1.altoR);
                g.fillRect(r2.x,r2.y,r2.anchoR,r2.altoR);
                g.fillOval(ball.x,ball.y,ball.radio,ball.radio);
                g.drawLine(getWidth()/2, 0, getWidth()/2, getHeight());
                g.drawString(""+puntos1, getWidth()/2-100, 20);
                g.drawString(""+puntos2, getWidth()/2+100, 20);
                g.drawString("Espectador", 20, 280);
                g.drawString("Jesus Reyes V-24152665 Programacion II  2015-1", 20, 300);
            }
            
        };
        
        //this.setLayout(null);
        
        //cambiarLabels(puntos1,puntos2);
        
        this.add(panel,0);
        conectar();
    }

    @Override
    public void conectar() {
        if(!conectado){
            try {
                cliente = new Socket("localhost",40000);
                conectado = true;
                ois = new ObjectInputStream(cliente.getInputStream());
            } catch (IOException ex) {
                Logger.getLogger(ClientFrame.class.getName()).log(Level.SEVERE, null, ex);
                JOptionPane.showMessageDialog(auto, "Debe abrir primero la aplicacion servidor antes de poder usar los espectadores.(Cerrar y volver a abrir esta aplicacion luego de abrir el servidor)");
            }
            
            if(cliente != null && ois!= null && conectado){
                Thread lectura = new Thread(new Runnable() {

                    @Override
                    public void run() {
                        while(true){
                            Object o = null;
                            MensajeMovimiento msjM = null;
                            MensajeGanador mg = null;
                            try {
                                o = ois.readObject();
                            } catch (IOException ex) {
                                Logger.getLogger(ClientFrame.class.getName()).log(Level.SEVERE, null, ex);
                            } catch (ClassNotFoundException ex) {
                                Logger.getLogger(ClientFrame.class.getName()).log(Level.SEVERE, null, ex);
                            }
                            try{msjM = (MensajeMovimiento)o;}catch(Exception e){}
                            
                            try{mg = (MensajeGanador)o;}catch(Exception e){}
                            
                            if(msjM != null){
                                synchronized(ball){
                                    ball.x = msjM.bx;
                                    ball.y = msjM.by;
                                }
                                
                                synchronized(r1){
                                    r1.x = msjM.r1x;
                                    r1.y = msjM.r1y;
                                }
                                
                                synchronized(r2){
                                    r2.x = msjM.r2x;
                                    r2.y = msjM.r2y;
                                }
                                
                                puntos1 = msjM.puntos1;
                                puntos2 = msjM.puntos2;
                                repaint();
                                
                                System.out.println("recibido "+msjM.r1x+" "+msjM.r1y+" "+msjM.r2x+" "+msjM.r2y);
                            }
                            
                            if(mg != null){
                                JOptionPane.showMessageDialog(auto, mg.m);
                            }
                        }
                    }
                });
                System.out.println("conectado");
                
                lectura.start();
            }
        }
    }

    @Override
    public void enviar(Object o) {
        
    }
    
    
    
}
