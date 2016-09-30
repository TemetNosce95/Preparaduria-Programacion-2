/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GameFrame;

import java.awt.Graphics;
import java.awt.Rectangle;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.io.IOException;
import java.io.ObjectOutput;
import java.io.ObjectOutputStream;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Random;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;

/**
 *
 * @author geiver.botello
 */
public class GameFrame extends JFrame implements Conexion{
    Ball ball;
    Raqueta r1,r2;
    int puntos1,puntos2;
    GameFrame auto = this;
    
    static Random rnd = new Random();
    Thread thread_pelota = null;
    boolean juegoActivo;
    
    ServerSocket server;
    Thread aceptarClientes;
    ArrayList<ObjectOutputStream> ooss;
    boolean conectado;

    public GameFrame() {
        this.setVisible(true);
        this.setBounds(50, 50, 500, 350);
        this.setDefaultCloseOperation(EXIT_ON_CLOSE);
        
        JPanel panel = new JPanel(){

            @Override
            protected void paintComponent(Graphics g) {
                super.paintComponent(g); //To change body of generated methods, choose Tools | Templates.
                if(juegoActivo){
                    g.fillRect(r1.x,r1.y,r1.anchoR,r1.altoR);
                    g.fillRect(r2.x,r2.y,r2.anchoR,r2.altoR);
                    g.fillOval(ball.x,ball.y,ball.radio,ball.radio);
                    g.drawLine(getWidth()/2, 0, getWidth()/2, getHeight());
                    g.drawString(""+puntos1, getWidth()/2-100, 20);
                    g.drawString(""+puntos2, getWidth()/2+100, 20);
                }
                g.drawString("Presione N para juego nuevo", 20, 280);
                g.drawString("Arriba: flecha UP, W; Abajo: flecha DOWN,S", getWidth()/2, 280);
                g.drawString("Jesus Reyes V-24152665 Programacion II 2015-1", 20, 300);
            }
            
        };
        
        //this.setLayout(null);
        
        //cambiarLabels(puntos1,puntos2);
        
        this.add(panel,0);
        panel.setFocusable(true);
        //this.add(p1,0);
        //this.add(p2,0);
        
        addKeyListener(new KeyListener() {

            @Override
            public void keyTyped(KeyEvent e) {
                //throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
            }

            @Override
            public void keyPressed(KeyEvent e) {
                if(e.getKeyCode() == KeyEvent.VK_UP)
                    r2.mover(getWidth(), getHeight(), -1);
                else
                    if(e.getKeyCode() == KeyEvent.VK_DOWN)
                        r2.mover(getWidth(), getHeight(), 1);
                    else
                        if(e.getKeyCode() == KeyEvent.VK_W)
                            r1.mover(getWidth(), getHeight(), -1);
                        else
                            if(e.getKeyCode() == KeyEvent.VK_S)
                                r1.mover(getWidth(), getHeight(), 1);
                repaint();
            }

            @Override
            public void keyReleased(KeyEvent e) {
                if(e.getKeyCode() == KeyEvent.VK_N){
                    
                    if(juegoActivo){
                        String m = null;
                        if(puntos1 > puntos2)
                            m = "Gano el jugador # 1.";
                        else
                            if(puntos2 > puntos1)
                                m = "Gano el jugador # 2.";
                            else
                                m = "Empate";
                        JOptionPane.showMessageDialog(auto, m);
                        MensajeGanador mg = new MensajeGanador();
                        mg.m = m;
                        enviar(mg);
                    }
                    
                    
                    juegoActivo = false;
                    
                    
                    
                    try {
                        Thread.sleep(1000);
                    } catch (InterruptedException ex) {
                        Logger.getLogger(GameFrame.class.getName()).log(Level.SEVERE, null, ex);
                    }
                    iniciarJuego();
                }
            }
        });
        System.out.println("hola");
        ooss = new ArrayList<>();
        conectado = true;
        conectar();
        //iniciarJuego();
    }
    
    void instanciarTPelotaYRaquetas(){
        this.ball = new Ball(getWidth()/2,getHeight()/2,(rnd.nextInt()%2==0)?1:-1,(rnd.nextInt()%2==0)?1:-1);
        this.r1 = new Raqueta(0,getHeight()/2-Raqueta.altoR/2);
        this.r2 = new Raqueta(getWidth()-Raqueta.anchoR,getHeight()/2-Raqueta.altoR/2);
        
        thread_pelota = new Thread(new Runnable() {

            @Override
            public void run() {
                while(juegoActivo){
                    ball.mover(getWidth(), getHeight());
                    Rectangle rectb = null,rectR1 = null, rectR2 = null;
                    boolean pelotaSubiendo = false;
                    int dx, dy;
                    
                    synchronized(ball){
                        rectb = (Rectangle) ball.rect.clone();
                        pelotaSubiendo = ball.isSubiendo();
                        dx = ball.dx;
                        dy = ball.dy;
                    }
                    
                    if(rectb.x >= -50 && rectb.x <= getWidth()+50){
                        synchronized(r1){
                            rectR1 = (Rectangle)r1.rect.clone();
                        }

                        synchronized(r2){
                            rectR2 = (Rectangle)r2.rect.clone();
                        }



                        if(rectb.intersects(rectR1)){
                            if(rectb.getY() > (rectR1.getY() + Raqueta.altoR/2 -10) && pelotaSubiendo)
                                dy = 1;
                            else
                                if(rectb.getY() >= (rectR1.getY() + Raqueta.altoR/2-10) && rectb.getY() <= (rectR1.getY() + Raqueta.altoR/2-10))
                                    dy = 0;
                                else
                                    dy = -1;

                            dx = 1;
                        }

                        if(rectb.intersects(rectR2)){
                            if(rectb.getY() > (rectR2.getY() + Raqueta.altoR/2 - 10) && pelotaSubiendo)
                                dy = 1;
                            else
                                if(rectb.getY() == (rectR2.getY() + Raqueta.altoR/2 - 10) && rectb.getY() <= (rectR2.getY() + Raqueta.altoR/2-10))
                                    dy = 0;
                                else
                                    dy = -1;

                            dx = -1;
                        }

                        if(rectb.y <= 0)
                            dy = 1;
                        else
                            if(rectb.y + rectb.height >= getHeight() )
                                dy = -1;


                        synchronized(ball){
                            ball.cambiarDireccion(dx, dy);
                            //System.out.println("ball x="+ball.x+"/y="+ball.y);
                        }
                        try {
                            Thread.sleep(40);
                        } catch (InterruptedException ex) {
                            Logger.getLogger(GameFrame.class.getName()).log(Level.SEVERE, null, ex);
                        }
                    }else{
                        if(rectb.x <= 0){
                            dx = 1;
                            puntos2++;
                        }
                        
                        if(rectb.x >= getWidth()){
                            dx = -1;
                            puntos1++;
                        }
                        
                        dy = (rnd.nextInt()%2==0)?1:-1;
                        
                        synchronized(ball){
                            ball.cambiarDireccion(dx, dy);
                            ball.x = getWidth()/2;
                            ball.y = getHeight()/2;
                        }
                    }
                    
                    MensajeMovimiento m = new MensajeMovimiento();
                    m.bx = rectb.x; m.by = rectb.y;
                    m.r1x = r1.x;m.r1y = r1.y;
                    m.r2x = r2.x;m.r2y = r2.y;
                    
                    m.puntos1 = puntos1;
                    m.puntos2 = puntos2;
                    
                    //System.out.println("recibido "+m.r1x+" "+m.r1y+" "+m.r2x+" "+m.r2y);
                    enviar(m);
                    
                    repaint();
                }
            }
        });
        
        thread_pelota.start();
    }
    
    void iniciarJuego(){
        puntos2 = puntos1 = 0;
        juegoActivo = true;
        instanciarTPelotaYRaquetas();
    }

    @Override
    public void conectar() {
        try {
            server = new ServerSocket(40000);
        } catch (IOException ex) {
            Logger.getLogger(GameFrame.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        if(server != null){
            aceptarClientes = new Thread(new Runnable() {

                @Override
                public void run() {
                    System.out.println("esperando clientes");
                    while(conectado){
                        Socket cliente = null;
                        
                        try {
                            cliente = server.accept();
                            System.out.println("nuevo cliente agregado.");
                            ObjectOutputStream oos = new ObjectOutputStream(cliente.getOutputStream());
                            
                            synchronized(ooss){
                                ooss.add(oos);
                            }
                        } catch (IOException ex) {
                            Logger.getLogger(GameFrame.class.getName()).log(Level.SEVERE, null, ex);
                        }
                    }
                }
            });
                
            aceptarClientes.start();
        }
        
    }

    @Override
    public void enviar(Object o) {
        if(conectado){
            synchronized(ooss){
                for(ObjectOutputStream oos:ooss){
                    try {
                        oos.writeObject(o);
                    } catch (IOException ex) {
                        Logger.getLogger(GameFrame.class.getName()).log(Level.SEVERE, null, ex);
                    }
                }
            }
        }
    }
}
